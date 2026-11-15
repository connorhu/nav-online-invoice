<?php

namespace NAV\OnlineInvoice\Tests\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Response\QueryInvoiceCheckResponse;
use NAV\OnlineInvoice\Providers\ResponseClassProvider;
use NAV\OnlineInvoice\Serializer\Encoder\RequestEncoder;
use NAV\OnlineInvoice\Serializer\Normalizers\QueryInvoiceCheckResponseDenormalizer;
use NAV\OnlineInvoice\Validator\XSDValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Serializer;

#[CoversClass(QueryInvoiceCheckResponseDenormalizer::class)]
class QueryInvoiceCheckResponseDenormalizerTest extends TestCase
{
    public static function fixtures(): iterable
    {
        yield 'true' => ['query_invoice_check_response_true.xml', true];
        yield 'false' => ['query_invoice_check_response_false.xml', false];
    }

    #[DataProvider('fixtures')]
    public function testFixtureIsValidAgainstTheApiSchema(string $fixture): void
    {
        $validator = new XSDValidator(__DIR__.'/../../Fixtures/invoice_api_catalog.xsd');
        $validator->validate(file_get_contents(__DIR__.'/../../Fixtures/'.$fixture));

        $this->assertSame([], array_map(fn (\LibXMLError $error) => trim($error->message), $validator->getErrors()));
    }

    #[DataProvider('fixtures')]
    public function testInvoiceCheckResult(string $fixture, bool $expected): void
    {
        $xml = file_get_contents(__DIR__.'/../../Fixtures/'.$fixture);

        // same path as OnlineInvoiceRestClient::sendRequest()
        $serializer = new Serializer([new QueryInvoiceCheckResponseDenormalizer()], [new RequestEncoder()]);
        $response = $serializer->deserialize($xml, ResponseClassProvider::getResponseClass($xml), 'request');

        $this->assertInstanceOf(QueryInvoiceCheckResponse::class, $response);
        $this->assertSame($expected, $response->getInvoiceCheckResult());
    }

    public function testPrefixedApiNamespace(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><ns2:QueryInvoiceCheckResponse xmlns="http://schemas.nav.gov.hu/NTCA/1.0/common" xmlns:ns2="http://schemas.nav.gov.hu/OSA/3.0/api">'
            .'<header><requestId>RID1</requestId><timestamp>2024-02-01T10:00:01.123Z</timestamp><requestVersion>3.0</requestVersion><headerVersion>1.0</headerVersion></header>'
            .'<result><funcCode>OK</funcCode></result><ns2:invoiceCheckResult>true</ns2:invoiceCheckResult></ns2:QueryInvoiceCheckResponse>';

        $serializer = new Serializer([new QueryInvoiceCheckResponseDenormalizer()], [new RequestEncoder()]);
        $response = $serializer->deserialize($xml, ResponseClassProvider::getResponseClass($xml), 'request');

        $this->assertInstanceOf(QueryInvoiceCheckResponse::class, $response);
        $this->assertTrue($response->getInvoiceCheckResult());
    }
}
