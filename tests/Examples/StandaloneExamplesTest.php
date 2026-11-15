<?php

namespace NAV\OnlineInvoice\Tests\Examples;

use NAV\OnlineInvoice\Exceptions\TokenExchangeException;
use NAV\OnlineInvoice\Http\Request\ManageInvoiceRequest;
use NAV\OnlineInvoice\Http\Request\QueryInvoiceDataRequest;
use NAV\OnlineInvoice\Http\Request\QueryInvoiceDigestRequest;
use NAV\OnlineInvoice\Http\Request\QueryTaxpayerRequest;
use NAV\OnlineInvoice\Http\Request\QueryTransactionStatusRequest;
use NAV\OnlineInvoice\Http\Request\TokenExchangeRequest;
use NAV\OnlineInvoice\Http\Response\ErrorResponse;
use NAV\OnlineInvoice\Http\Response\GeneralErrorResponse;
use NAV\OnlineInvoice\Http\Response\ManageInvoiceResponse;
use NAV\OnlineInvoice\Http\Response\TokenExchangeResponse;
use NAV\OnlineInvoice\Model\Enums\InvoiceOperationEnum;
use NAV\OnlineInvoice\Model\InvoiceOperation;
use NAV\OnlineInvoice\OnlineInvoiceRestClient;
use NAV\OnlineInvoice\Validator\XSDValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

require_once __DIR__.'/../../examples/standalone/bootstrap.php';
require_once __DIR__.'/../../examples/standalone/invoice.php';

/**
 * Builds the client exactly as examples/standalone/bootstrap.php does and runs the
 * example requests against a MockHttpClient (no network).
 */
class StandaloneExamplesTest extends TestCase
{
    private const API = 'xmlns="http://schemas.nav.gov.hu/OSA/3.0/api" xmlns:common="http://schemas.nav.gov.hu/NTCA/1.0/common"';
    private const HEADER = '<common:header><common:requestId>RID</common:requestId><common:timestamp>2024-01-15T10:00:00.000Z</common:timestamp><common:requestVersion>3.0</common:requestVersion><common:headerVersion>1.0</common:headerVersion></common:header>';

    /** @var list<array{url: string, body: string}> */
    private array $sent = [];

    private function createClient(callable $responder): OnlineInvoiceRestClient
    {
        $httpClient = new MockHttpClient(function (string $method, string $url, array $options) use ($responder) {
            $this->sent[] = ['url' => $url, 'body' => $options['body']];

            return $responder($url);
        });

        return initClient($httpClient, __DIR__.'/../Fixtures/compact_data_provider_info.json');
    }

    private static function errorResponse(): MockResponse
    {
        return new MockResponse(
            '<?xml version="1.0" encoding="UTF-8"?><GeneralErrorResponse '.self::API.'>'.self::HEADER
            .'<common:result><common:funcCode>ERROR</common:funcCode><common:errorCode>INVALID_SECURITY_USER</common:errorCode><common:message>Helytelen authentikációs adatok!</common:message></common:result></GeneralErrorResponse>',
            ['http_code' => 401],
        );
    }

    private static function tokenExchangeResponse(): MockResponse
    {
        $token = openssl_encrypt('decoded-token', 'AES-128-ECB', 'exchange-key');

        return new MockResponse(
            '<?xml version="1.0" encoding="UTF-8"?><TokenExchangeResponse '.self::API.'>'.self::HEADER
            .'<common:result><common:funcCode>OK</common:funcCode></common:result>'
            .'<encodedExchangeToken>'.$token.'</encodedExchangeToken>'
            .'<tokenValidityFrom>2024-01-15T10:00:00.000Z</tokenValidityFrom><tokenValidityTo>2024-01-15T10:05:00.000Z</tokenValidityTo></TokenExchangeResponse>',
        );
    }

    public static function requests(): iterable
    {
        yield 'TokenExchangeRequest.php' => [fn () => new TokenExchangeRequest(), '/invoiceService/v3/tokenExchange', 'TokenExchangeRequest'];

        yield 'QueryTaxpayerRequest.php' => [function () {
            $request = new QueryTaxpayerRequest();
            $request->setTaxNumber('69061864');

            return $request;
        }, '/invoiceService/v3/queryTaxpayer', 'QueryTaxpayerRequest'];

        yield 'QueryInvoiceData.php' => [function () {
            $request = new QueryInvoiceDataRequest();
            $request->setInvoiceDirection(QueryInvoiceDataRequest::INVOICE_DIRECTION_INBOUND);
            $request->setInvoiceNumber('AHH/2021/0017024');

            return $request;
        }, '/invoiceService/v3/queryInvoiceData', 'QueryInvoiceDataRequest'];

        yield 'QueryInvoiceDigest.php' => [function () {
            $request = new QueryInvoiceDigestRequest();
            $request->setInvoiceDirection(QueryInvoiceDigestRequest::INVOICE_DIRECTION_INBOUND);
            $request->setPage(1);
            $request->setIssueDateFrom(new \DateTime('2021-03-01'));
            $request->setIssueDateTo(new \DateTime('2021-03-31'));

            return $request;
        }, '/invoiceService/v3/queryInvoiceDigest', 'QueryInvoiceDigestRequest'];

        yield 'QueryTransactionStatusRequest.php' => [function () {
            $request = new QueryTransactionStatusRequest();
            $request->setTransactionId('TX123');

            return $request;
        }, '/invoiceService/v3/queryTransactionStatus', 'QueryTransactionStatusRequest'];
    }

    /**
     * Every example request is validated, serialized and sent; NAV's error answer is
     * returned as an ErrorResponse (not thrown), which is what the examples check for.
     */
    #[DataProvider('requests')]
    public function testRequestIsSentAndErrorResponseIsReturned(callable $requestFactory, string $path, string $rootNode): void
    {
        $client = $this->createClient(fn () => self::errorResponse());

        $response = $client->sendRequest($requestFactory());

        $this->assertInstanceOf(GeneralErrorResponse::class, $response);
        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->assertSame('INVALID_SECURITY_USER', $response->getErrorCode());

        $this->assertCount(1, $this->sent);
        $this->assertSame('https://api-test.onlineszamla.nav.gov.hu'.$path, $this->sent[0]['url']);

        $document = new \DOMDocument();
        $this->assertTrue($document->loadXML($this->sent[0]['body']));
        $this->assertSame($rootNode, $document->documentElement->localName);
        $this->assertSame('http://schemas.nav.gov.hu/OSA/3.0/api', $document->documentElement->namespaceURI);
    }

    public function testTokenExchange(): void
    {
        $client = $this->createClient(fn () => self::tokenExchangeResponse());

        $response = $client->sendRequest(new TokenExchangeRequest());

        $this->assertInstanceOf(TokenExchangeResponse::class, $response);
        $this->assertSame('decoded-token', $response->getExchangeToken());
    }

    public function testTokenExchangeFailureThrows(): void
    {
        $client = $this->createClient(fn () => self::errorResponse());

        $this->expectException(TokenExchangeException::class);
        $this->expectExceptionMessage('INVALID_SECURITY_USER');

        $client->sendRequest($this->createManageInvoiceRequest());
    }

    public function testManageInvoice(): void
    {
        $client = $this->createClient(function (string $url) {
            if (str_ends_with($url, '/tokenExchange')) {
                return self::tokenExchangeResponse();
            }

            return new MockResponse(
                '<?xml version="1.0" encoding="UTF-8"?><ManageInvoiceResponse '.self::API.'>'.self::HEADER
                .'<common:result><common:funcCode>OK</common:funcCode></common:result><transactionId>TX123</transactionId></ManageInvoiceResponse>',
            );
        });

        $response = $client->sendRequest($this->createManageInvoiceRequest());

        $this->assertInstanceOf(ManageInvoiceResponse::class, $response);
        $this->assertSame('TX123', $response->getTransactionId());

        $this->assertCount(2, $this->sent);
        $this->assertStringEndsWith('/invoiceService/v3/tokenExchange', $this->sent[0]['url']);
        $this->assertStringEndsWith('/invoiceService/v3/manageInvoice', $this->sent[1]['url']);

        $document = new \DOMDocument();
        $this->assertTrue($document->loadXML($this->sent[1]['body']));
        $xpath = new \DOMXPath($document);
        $xpath->registerNamespace('api', 'http://schemas.nav.gov.hu/OSA/3.0/api');

        $this->assertSame('decoded-token', $xpath->evaluate('string(/api:ManageInvoiceRequest/api:exchangeToken)'));
        $this->assertSame('CREATE', $xpath->evaluate('string(//api:invoiceOperation/api:invoiceOperation)'));

        // the invoice data sent to NAV passes the invoiceData XSD shipped with the library
        $invoiceXml = base64_decode($xpath->evaluate('string(//api:invoiceOperation/api:invoiceData)'), true);
        $this->assertNotFalse($invoiceXml);

        $validator = new XSDValidator(__DIR__.'/../../lib/Resources/XSD/catalog.xsd');
        $validator->validate($invoiceXml);
        $this->assertSame([], array_map(fn (\LibXMLError $error) => trim($error->message), $validator->getErrors()));
        $this->assertStringContainsString('<invoiceNumber>EXAMPLE/2024/0001</invoiceNumber>', $invoiceXml);
    }

    public function testInfoJsonDistHasTheKeysCompactDataProviderReads(): void
    {
        $info = json_decode(file_get_contents(__DIR__.'/../../examples/standalone/info.json.dist'), true, flags: JSON_THROW_ON_ERROR);

        foreach (['login', 'password', 'taxNumber', 'signKey', 'xmlExchangeKey'] as $key) {
            $this->assertArrayHasKey($key, $info['user']);
        }

        foreach (['id', 'name', 'operation', 'mainVersion', 'devName', 'devContact', 'devCountryCode', 'devTaxNumber'] as $key) {
            $this->assertArrayHasKey($key, $info['software']);
        }

        $this->assertArrayHasKey('requestPrefix', $info);
        $this->assertArrayHasKey('test', $info);
    }

    private function createManageInvoiceRequest(): ManageInvoiceRequest
    {
        $request = new ManageInvoiceRequest();
        $request->addInvoiceOperation(new InvoiceOperation(InvoiceOperationEnum::Create, createExampleInvoice()));

        return $request;
    }
}
