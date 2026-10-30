<?php

namespace NAV\OnlineInvoice\Tests;

use NAV\OnlineInvoice\Http\Request\TokenExchangeRequest;
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
}
