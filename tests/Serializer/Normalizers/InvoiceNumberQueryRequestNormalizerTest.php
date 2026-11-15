<?php

namespace NAV\OnlineInvoice\Tests\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Enums\RequestVersionEnum;
use NAV\OnlineInvoice\Http\Request\Header;
use NAV\OnlineInvoice\Http\Request\InvoiceNumberQueryRequest;
use NAV\OnlineInvoice\Http\Request\QueryInvoiceCheckRequest;
use NAV\OnlineInvoice\Http\Request\QueryInvoiceDataRequest;
use NAV\OnlineInvoice\Http\Request\Software;
use NAV\OnlineInvoice\Http\Request\User;
use NAV\OnlineInvoice\Serializer\Encoder\RequestEncoder;
use NAV\OnlineInvoice\Serializer\Normalizers\HeaderNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\InvoiceNumberQueryNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\QueryInvoiceCheckRequestNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\QueryInvoiceDataRequestNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\RequestNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\UserNormalizer;
use NAV\OnlineInvoice\Tests\Fixtures\CryptoToolsProvider;
use NAV\OnlineInvoice\Validator\XSDValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;

/**
 * QueryInvoiceCheckRequest and QueryInvoiceDataRequest share InvoiceNumberQueryType;
 * the serialized requests are validated against the NAV invoiceApi.xsd v3.0.
 */
#[CoversClass(QueryInvoiceCheckRequestNormalizer::class)]
#[CoversClass(QueryInvoiceDataRequestNormalizer::class)]
#[CoversClass(InvoiceNumberQueryNormalizer::class)]
class InvoiceNumberQueryRequestNormalizerTest extends TestCase
{
    private const API_SCHEMA = __DIR__.'/../../Fixtures/invoice_api_catalog.xsd';

    private Serializer $serializer;

    protected function setUp(): void
    {
        $cryptoTools = new CryptoToolsProvider();
        $requestNormalizer = new RequestNormalizer($cryptoTools);

        $this->serializer = new Serializer([
            $requestNormalizer,
            new QueryInvoiceCheckRequestNormalizer($requestNormalizer),
            new QueryInvoiceDataRequestNormalizer($requestNormalizer),
            new UserNormalizer($cryptoTools),
            new SoftwareNormalizer(),
            new HeaderNormalizer(),
        ], [new RequestEncoder()]);

        $requestNormalizer->setSerializer($this->serializer);
    }

    public static function requestClasses(): iterable
    {
        yield 'QueryInvoiceCheckRequest' => [QueryInvoiceCheckRequest::class, '/queryInvoiceCheck'];
        yield 'QueryInvoiceDataRequest' => [QueryInvoiceDataRequest::class, '/queryInvoiceData'];
    }

    /**
     * @param class-string<QueryInvoiceCheckRequest|QueryInvoiceDataRequest> $class
     */
    private function createRequest(string $class): QueryInvoiceCheckRequest|QueryInvoiceDataRequest
    {
        $header = new Header();
        $header->setTimestamp(new \DateTimeImmutable('2024-01-15 10:00:00 UTC'));

        $software = new Software();
        $software->setId('HU69061864-1234567');
        $software->setName('Nav api test @ php');
        $software->setOperation(Software::OPERATION_ONLINE_SERVICE);
        $software->setMainVersion('1.0');
        $software->setDevName('Developer');
        $software->setDevContact('dev@example.com');
        $software->setDevCountryCode('HU');
        $software->setDevTaxNumber('69061864');

        $user = new User();
        $user->setLogin('testuserlogin');
        $user->setSignKey('key');
        $user->setPassword('abc123');
        $user->setTaxNumber('12345678-2-11');

        $request = new $class();
        $request->setHeader($header);
        $request->setSoftware($software);
        $request->setUser($user);
        $request->setRequestId('RID1');
        $request->setRequestVersion(RequestVersionEnum::v30);

        return $request;
    }

    private function assertValidAgainstApiSchema(string $xml): void
    {
        $validator = new XSDValidator(self::API_SCHEMA);
        $validator->validate($xml);

        $this->assertSame([], array_map(fn (\LibXMLError $error) => trim($error->message), $validator->getErrors()));
    }

    #[DataProvider('requestClasses')]
    public function testRequiredFieldsOnly(string $class, string $endpointPath): void
    {
        $request = $this->createRequest($class);
        $request->setInvoiceNumber('INV/2024/0001');
        $request->setInvoiceDirection(InvoiceNumberQueryRequest::INVOICE_DIRECTION_OUTBOUND);

        $this->assertSame($endpointPath, $request->getEndpointPath());

        $normalized = $this->serializer->normalize($request, 'request');
        $this->assertSame($class::ROOT_NODE_NAME, $normalized['@root_node_name']);
        $this->assertSame([
            'invoiceNumber' => 'INV/2024/0001',
            'invoiceDirection' => 'OUTBOUND',
        ], $normalized['invoiceNumberQuery']);

        $this->assertValidAgainstApiSchema($this->serializer->serialize($request, 'request', [XmlEncoder::FORMAT_OUTPUT => true]));
    }

    #[DataProvider('requestClasses')]
    public function testAllFields(string $class): void
    {
        $request = $this->createRequest($class);
        $request->setInvoiceNumber('INV/2024/0002');
        $request->setInvoiceDirection(InvoiceNumberQueryRequest::INVOICE_DIRECTION_INBOUND);
        $request->setBatchIndex(2);
        $request->setSupplierTaxNumber('87654321-2-41');

        $xml = $this->serializer->serialize($request, 'request', [XmlEncoder::FORMAT_OUTPUT => true]);

        $this->assertValidAgainstApiSchema($xml);

        $document = new \DOMDocument();
        $document->loadXML($xml);
        $xpath = new \DOMXPath($document);
        $xpath->registerNamespace('api', 'http://schemas.nav.gov.hu/OSA/3.0/api');

        $this->assertSame($class::ROOT_NODE_NAME, $document->documentElement->localName);
        $this->assertSame('INV/2024/0002', $xpath->evaluate('string(/api:*/api:invoiceNumberQuery/api:invoiceNumber)'));
        $this->assertSame('INBOUND', $xpath->evaluate('string(/api:*/api:invoiceNumberQuery/api:invoiceDirection)'));
        $this->assertSame('2', $xpath->evaluate('string(/api:*/api:invoiceNumberQuery/api:batchIndex)'));
        $this->assertSame('87654321', $xpath->evaluate('string(/api:*/api:invoiceNumberQuery/api:supplierTaxNumber)'));
    }
}
