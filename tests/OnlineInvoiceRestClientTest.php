<?php

namespace NAV\OnlineInvoice\Tests;

use NAV\OnlineInvoice\Exceptions\TokenExchangeException;
use NAV\OnlineInvoice\Http\Request\TokenExchangeRequest;
use NAV\OnlineInvoice\Http\Response\GeneralErrorResponse;
use NAV\OnlineInvoice\Http\Response\TokenExchangeResponse;
use NAV\OnlineInvoice\Http\Request\Software;
use NAV\OnlineInvoice\Http\Request\User;
use NAV\OnlineInvoice\OnlineInvoiceRestClient;
use NAV\OnlineInvoice\Providers\ApiEndpointUrlProviderInterface;
use NAV\OnlineInvoice\Providers\RequestIdProviderInterface;
use NAV\OnlineInvoice\Providers\SoftwareProviderInterface;
use NAV\OnlineInvoice\Providers\UserProviderInterface;
use NAV\OnlineInvoice\Validator\Exceptions\InvalidXMLException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[CoversClass(OnlineInvoiceRestClient::class)]
class OnlineInvoiceRestClientTest extends TestCase
{
    public function testInvalidXmlIsRethrownToTheCaller(): void
    {
        $validator = $this->createStub(ValidatorInterface::class);
        $validator->method('validate')->willReturn(new ConstraintViolationList());

        $thrown = new InvalidXMLException([], '<broken/>');
        $serializer = $this->createStub(SerializerInterface::class);
        $serializer->method('serialize')->willThrowException($thrown);

        $softwareProvider = $this->createStub(SoftwareProviderInterface::class);
        $softwareProvider->method('getSoftware')->willReturn(new Software());
        $userProvider = $this->createStub(UserProviderInterface::class);
        $userProvider->method('getUser')->willReturn(new User());
        $requestIdProvider = $this->createStub(RequestIdProviderInterface::class);
        $requestIdProvider->method('getRequestId')->willReturn('RID1');

        $client = new OnlineInvoiceRestClient(
            $softwareProvider,
            $validator,
            $serializer,
            $requestIdProvider,
            $userProvider,
            $this->createStub(ApiEndpointUrlProviderInterface::class),
        );

        try {
            $client->sendRequest(new TokenExchangeRequest());
            $this->fail('sendRequest() must throw instead of swallowing the failure');
        } catch (InvalidXMLException $caught) {
            $this->assertSame($thrown, $caught);
            $this->assertSame('<broken/>', $caught->getXmlContent());
        }
    }

    public function testTokenExchangeErrorIsThrownWithTheErrorResponse(): void
    {
        $errorResponse = (new GeneralErrorResponse())
            ->setFunctionCode('ERROR')
            ->setErrorCode('INVALID_SECURITY_USER')
            ->setMessage('Helytelen authentikációs adatok!');

        $client = $this->createTokenExchangeClient(
            new MockResponse('<GeneralErrorResponse/>', ['http_code' => 401]),
            $errorResponse,
        );

        try {
            $client->getExhangeToken();
            $this->fail('getExhangeToken() must throw when NAV answers with an error');
        } catch (TokenExchangeException $caught) {
            $this->assertSame($errorResponse, $caught->getResponse());
            $this->assertStringContainsString('INVALID_SECURITY_USER', $caught->getMessage());
        }
    }

    public function testTokenExchangeReturnsTheToken(): void
    {
        $client = $this->createTokenExchangeClient(
            new MockResponse('<TokenExchangeResponse/>', ['http_code' => 200]),
            (new TokenExchangeResponse())->setExchangeToken('decoded-token'),
        );

        $this->assertSame('decoded-token', $client->getExhangeToken());
    }

    private function createTokenExchangeClient(MockResponse $httpResponse, object $deserialized): OnlineInvoiceRestClient
    {
        $validator = $this->createStub(ValidatorInterface::class);
        $validator->method('validate')->willReturn(new ConstraintViolationList());

        $serializer = $this->createStub(SerializerInterface::class);
        $serializer->method('serialize')->willReturn('<TokenExchangeRequest/>');
        $serializer->method('deserialize')->willReturn($deserialized);

        $softwareProvider = $this->createStub(SoftwareProviderInterface::class);
        $softwareProvider->method('getSoftware')->willReturn(new Software());
        $userProvider = $this->createStub(UserProviderInterface::class);
        $userProvider->method('getUser')->willReturn(new User());
        $requestIdProvider = $this->createStub(RequestIdProviderInterface::class);
        $requestIdProvider->method('getRequestId')->willReturn('RID1');
        $urlProvider = $this->createStub(ApiEndpointUrlProviderInterface::class);
        $urlProvider->method('getEndpointUrl')->willReturn('https://api-test.onlineszamla.nav.gov.hu/invoiceService/v3/tokenExchange');

        return new OnlineInvoiceRestClient(
            $softwareProvider,
            $validator,
            $serializer,
            $requestIdProvider,
            $userProvider,
            $urlProvider,
            null,
            new MockHttpClient($httpResponse),
        );
    }
}
