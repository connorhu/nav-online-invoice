<?php

namespace NAV\OnlineInvoice\Tests\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Enums\RequestVersionEnum;
use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\Header;
use NAV\OnlineInvoice\Http\Request\ManageInvoiceRequest;
use NAV\OnlineInvoice\Http\Request\Software;
use NAV\OnlineInvoice\Http\Request\User;
use NAV\OnlineInvoice\Model\Enums\InvoiceOperationEnum;
use NAV\OnlineInvoice\Model\Invoice;
use NAV\OnlineInvoice\Model\InvoiceOperation;
use NAV\OnlineInvoice\Serializer\Encoder\RequestEncoder;
use NAV\OnlineInvoice\Serializer\Normalizers;
use NAV\OnlineInvoice\Tests\Fixtures\ContentAwareCryptoToolsProvider;
use NAV\OnlineInvoice\Validator\XSDValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

require_once __DIR__.'/../../../examples/standalone/bootstrap.php';
require_once __DIR__.'/../../../examples/standalone/invoice.php';

#[CoversClass(Normalizers\ManageInvoiceRequestNormalizer::class)]
#[CoversClass(InvoiceOperation::class)]
class ManageInvoiceRequestNormalizerTest extends TestCase
{
    private const SHA3_512_HASH = 'A69F73CCA23A9AC5C8B567DC185A756E97C982164FE25859E0D1DCC1475C80A615B2123AF1F5F94C11E3E9402C3AC558F500199D95B6D3E301758586281DCD26';
    private const SHA_256_HASH = 'E3B0C44298FC1C149AFBF4C8996FB92427AE41E4649B934CA495991B7852B855';

    private ContentAwareCryptoToolsProvider $cryptoTools;
    private Serializer $serializer;

    protected function setUp(): void
    {
        // encodes the invoice data like the real providers do, so the request is XSD-valid
        $this->cryptoTools = new class() extends ContentAwareCryptoToolsProvider {
            public function encodeInvoiceData(string $invoiceData, ?Request $request = null): string
            {
                return base64_encode($invoiceData);
            }
        };

        $requestNormalizer = new Normalizers\RequestNormalizer($this->cryptoTools);
        $vatRateNormalizer = new Normalizers\VatRateNormalizer(new \ExampleVatRateFactory());
        $vatRateSummaryNormalizer = new Normalizers\VatRateSummaryNormalizer($vatRateNormalizer);

        $this->serializer = new Serializer([
            $requestNormalizer,
            new Normalizers\ManageInvoiceRequestNormalizer($requestNormalizer, $this->cryptoTools),
            new Normalizers\UserNormalizer($this->cryptoTools),
            new Normalizers\SoftwareNormalizer(),
            new Normalizers\HeaderNormalizer(),
            new Normalizers\InvoiceNormalizer($vatRateSummaryNormalizer),
            new Normalizers\InvoiceItemNormalizer($vatRateNormalizer),
            $vatRateSummaryNormalizer,
            $vatRateNormalizer,
            new Normalizers\ProductCodeNormalizer(),
            new Normalizers\AddressNormalizer(),
            new DateTimeNormalizer(),
        ], [new RequestEncoder()]);
    }

    private static function createRequest(InvoiceOperation ...$operations): ManageInvoiceRequest
    {
        $request = new ManageInvoiceRequest();
        $request->setRequestVersion(RequestVersionEnum::v30);
        $request->setRequestId('RID1');
        $request->setHeader((new Header())->setTimestamp(new \DateTimeImmutable('2024-01-15 10:00:00 UTC')));
        $request->setExchangeToken('exchange-token');

        $user = new User();
        $user->setLogin('testlogin');
        $user->setPassword('password');
        $user->setTaxNumber('12345678');
        $user->setSignKey('key');
        $request->setUser($user);

        $software = new Software();
        $software->setId('HU12345678-TEST001');
        $software->setName('Test');
        $software->setOperation(Software::OPERATION_ONLINE_SERVICE);
        $software->setMainVersion('1.0');
        $software->setDevName('Developer');
        $software->setDevContact('dev@example.com');
        $software->setDevCountryCode('HU');
        $software->setDevTaxNumber('12345678');
        $request->setSoftware($software);

        foreach ($operations as $operation) {
            $request->addInvoiceOperation($operation);
        }

        return $request;
    }

    private static function createOperation(?Invoice $invoice = null): InvoiceOperation
    {
        return new InvoiceOperation(InvoiceOperationEnum::Create, $invoice ?? createExampleInvoice());
    }

    private function serialize(ManageInvoiceRequest $request): \DOMXPath
    {
        $xml = $this->serializer->serialize($request, 'request');

        $validator = new XSDValidator(__DIR__.'/../../Fixtures/invoice_api_catalog.xsd');
        $validator->validate($xml);
        $this->assertSame([], array_map(fn (\LibXMLError $error) => trim($error->message), $validator->getErrors()), 'the request is valid against invoiceApi.xsd');

        $document = new \DOMDocument();
        $document->loadXML($xml);
        $xpath = new \DOMXPath($document);
        $xpath->registerNamespace('api', 'http://schemas.nav.gov.hu/OSA/3.0/api');

        return $xpath;
    }

    /**
     * @return list<string>
     */
    private static function collectDeprecations(callable $callback): array
    {
        $deprecations = [];
        set_error_handler(static function (int $type, string $message) use (&$deprecations): bool {
            $deprecations[] = $message;

            return true;
        }, E_USER_DEPRECATED);

        try {
            $callback();
        } finally {
            restore_error_handler();
        }

        return $deprecations;
    }

    public function testRequestWithoutHashHasNoElectronicInvoiceHash(): void
    {
        $xpath = $this->serialize(self::createRequest(self::createOperation()));

        $this->assertSame(1, $xpath->query('//api:invoiceOperation/api:invoiceData')->length);
        $this->assertSame(0, $xpath->query('//api:electronicInvoiceHash')->length);
    }

    public function testHashIsSentInTheInvoiceOperationAfterInvoiceData(): void
    {
        $operation = self::createOperation()->setElectronicInvoiceHash(self::SHA3_512_HASH);

        $xpath = $this->serialize(self::createRequest($operation));

        $hash = $xpath->query('/api:ManageInvoiceRequest/api:invoiceOperations/api:invoiceOperation/api:electronicInvoiceHash');
        $this->assertSame(1, $hash->length);
        $this->assertSame(self::SHA3_512_HASH, $hash->item(0)->textContent);
        $this->assertSame('SHA3-512', $hash->item(0)->getAttribute('cryptoType'));
        $this->assertSame('invoiceData', $hash->item(0)->previousSibling->localName);
        $this->assertNull($hash->item(0)->nextSibling);

        // the invoice data itself does not carry the hash (it is not in invoiceData.xsd)
        $invoiceXml = base64_decode($xpath->evaluate('string(//api:invoiceOperation/api:invoiceData)'), true);
        $this->assertStringContainsString('<invoiceNumber>EXAMPLE/2024/0001</invoiceNumber>', $invoiceXml);
        $this->assertStringNotContainsString('electronicInvoiceHash', $invoiceXml);
        $this->assertStringNotContainsString(self::SHA3_512_HASH, $invoiceXml);
    }

    public function testHashWithSha256CryptoType(): void
    {
        $operation = self::createOperation()->setElectronicInvoiceHash(self::SHA_256_HASH, InvoiceOperation::ELECTRONIC_INVOICE_HASH_SHA_256);

        $xpath = $this->serialize(self::createRequest($operation));

        $this->assertSame(self::SHA_256_HASH, $xpath->evaluate('string(//api:invoiceOperation/api:electronicInvoiceHash)'));
        $this->assertSame('SHA-256', $xpath->evaluate('string(//api:invoiceOperation/api:electronicInvoiceHash/@cryptoType)'));
    }

    public function testHashIsPerOperation(): void
    {
        $withHash = self::createOperation()->setElectronicInvoiceHash(self::SHA3_512_HASH);

        $xpath = $this->serialize(self::createRequest(self::createOperation(), $withHash));

        $this->assertSame(0, $xpath->query('//api:invoiceOperation[api:index = 1]/api:electronicInvoiceHash')->length);
        $this->assertSame(self::SHA3_512_HASH, $xpath->evaluate('string(//api:invoiceOperation[api:index = 2]/api:electronicInvoiceHash)'));
    }

    public function testHashIsNotPartOfTheRequestSignature(): void
    {
        $this->serializer->normalize(self::createRequest(self::createOperation()), 'request');
        $this->serializer->normalize(self::createRequest(self::createOperation()->setElectronicInvoiceHash(self::SHA3_512_HASH)), 'request');

        $this->assertCount(2, $this->cryptoTools->signedContents);
        [$withoutHash, $withHash] = $this->cryptoTools->signedContents;

        // only invoiceOperation + invoiceData of each operation is signed
        $this->assertCount(1, $withHash);
        $this->assertStringStartsWith('CREATE', $withHash[0]);
        $this->assertSame($withoutHash, $withHash);
        $this->assertStringNotContainsString(self::SHA3_512_HASH, $withHash[0]);
    }

    public function testDeprecatedInvoiceHashIsSentInTheInvoiceOperation(): void
    {
        $invoice = createExampleInvoice();
        $deprecations = self::collectDeprecations(fn () => $invoice->setElectronicInvoiceHash(self::SHA3_512_HASH));

        $this->assertCount(1, $deprecations);
        $this->assertStringContainsString(InvoiceOperation::class.'::setElectronicInvoiceHash()', $deprecations[0]);

        $xpath = $this->serialize(self::createRequest(self::createOperation($invoice)));

        $this->assertSame(self::SHA3_512_HASH, $xpath->evaluate('string(//api:invoiceOperation/api:electronicInvoiceHash)'));
        $this->assertSame('SHA3-512', $xpath->evaluate('string(//api:invoiceOperation/api:electronicInvoiceHash/@cryptoType)'));
        $this->assertStringNotContainsString('electronicInvoiceHash', base64_decode($xpath->evaluate('string(//api:invoiceOperation/api:invoiceData)'), true));
    }

    public function testDeprecatedSha256InvoiceHashGetsSha256CryptoType(): void
    {
        $invoice = createExampleInvoice();
        self::collectDeprecations(fn () => $invoice->setElectronicInvoiceHash(self::SHA_256_HASH));

        $xpath = $this->serialize(self::createRequest(self::createOperation($invoice)));

        $this->assertSame(self::SHA_256_HASH, $xpath->evaluate('string(//api:invoiceOperation/api:electronicInvoiceHash)'));
        $this->assertSame('SHA-256', $xpath->evaluate('string(//api:invoiceOperation/api:electronicInvoiceHash/@cryptoType)'));
    }

    public function testOperationHashWinsOverDeprecatedInvoiceHash(): void
    {
        $invoice = createExampleInvoice();
        self::collectDeprecations(fn () => $invoice->setElectronicInvoiceHash(self::SHA_256_HASH));
        $operation = self::createOperation($invoice)->setElectronicInvoiceHash(self::SHA3_512_HASH);

        $xpath = $this->serialize(self::createRequest($operation));

        $this->assertSame(1, $xpath->query('//api:electronicInvoiceHash')->length);
        $this->assertSame(self::SHA3_512_HASH, $xpath->evaluate('string(//api:invoiceOperation/api:electronicInvoiceHash)'));
        $this->assertSame('SHA3-512', $xpath->evaluate('string(//api:invoiceOperation/api:electronicInvoiceHash/@cryptoType)'));
    }

    public function testSettingNoHashOnTheInvoiceIsNotDeprecated(): void
    {
        $this->assertSame([], self::collectDeprecations(fn () => createExampleInvoice()->setElectronicInvoiceHash(null)));
    }
}
