<?php

namespace NAV\OnlineInvoice\Tests\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Response\GeneralErrorResponse;
use NAV\OnlineInvoice\Http\Response\GeneralExceptionResponse;
use NAV\OnlineInvoice\Providers\ResponseClassProvider;
use NAV\OnlineInvoice\Serializer\Normalizers\ErrorResponseDenormalizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;

class ErrorResponseDenormalizerTest extends TestCase
{
    /**
     * A real response of the manageInvoice endpoint (HTTP 400) to a request with more than 100
     * invoice operations; the notification texts are shortened.
     */
    private const SCHEMA_VIOLATION_RESPONSE = <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?><GeneralExceptionResponse xmlns="http://schemas.nav.gov.hu/NTCA/1.0/common" xmlns:ns2="http://schemas.nav.gov.hu/OSA/3.0/api" xmlns:ns3="http://schemas.nav.gov.hu/OSA/3.0/base" xmlns:ns4="http://schemas.nav.gov.hu/OSA/3.0/data"><funcCode>ERROR</funcCode><errorCode>INVALID_REQUEST</errorCode><message>Érvénytelen kérés!</message><notifications><notification><notificationCode>SCHEMA_VIOLATION</notificationCode><notificationText>'invoiceOperation' can occur a maximum of '100' times in the current sequence.</notificationText></notification><notification><notificationCode>SCHEMA_VIOLATION</notificationCode><notificationText>Value '101' is not facet-valid with respect to maxInclusive '100' for type 'InvoiceIndexType'.</notificationText></notification></notifications></GeneralExceptionResponse>
XML;

    private function deserialize(string $xml): object
    {
        $serializer = new Serializer([new ErrorResponseDenormalizer()], [new XmlEncoder()]);

        return $serializer->deserialize($xml, ResponseClassProvider::getResponseClass($xml, 'xml'), 'xml');
    }

    public function testGeneralExceptionResponseReadsTheCodesFromTheRoot(): void
    {
        $response = $this->deserialize(self::SCHEMA_VIOLATION_RESPONSE);

        $this->assertInstanceOf(GeneralExceptionResponse::class, $response);
        $this->assertSame('ERROR', $response->getFunctionCode());
        $this->assertSame('INVALID_REQUEST', $response->getErrorCode());
        $this->assertSame('Érvénytelen kérés!', $response->getMessage());
    }

    public function testGeneralExceptionResponseKeepsEveryNotification(): void
    {
        $response = $this->deserialize(self::SCHEMA_VIOLATION_RESPONSE);

        $this->assertInstanceOf(GeneralExceptionResponse::class, $response);
        $notifications = $response->getNotifications();
        $this->assertCount(2, $notifications);
        $this->assertSame('SCHEMA_VIOLATION', $notifications[0]['code']);
        $this->assertStringContainsString("maximum of '100' times", (string) $notifications[0]['text']);
        $this->assertStringContainsString("Value '101'", (string) $notifications[1]['text']);
    }

    public function testASingleNotificationIsAlsoAList(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><GeneralExceptionResponse xmlns="http://schemas.nav.gov.hu/NTCA/1.0/common"><funcCode>ERROR</funcCode><errorCode>INVALID_REQUEST</errorCode><message>x</message><notifications><notification><notificationCode>SCHEMA_VIOLATION</notificationCode><notificationText>only one</notificationText></notification></notifications></GeneralExceptionResponse>';

        $response = $this->deserialize($xml);

        $this->assertInstanceOf(GeneralExceptionResponse::class, $response);
        $this->assertSame([['code' => 'SCHEMA_VIOLATION', 'text' => 'only one']], $response->getNotifications());
    }

    public function testAMissingElementLeavesTheValueNullInsteadOfThrowing(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><GeneralExceptionResponse xmlns="http://schemas.nav.gov.hu/NTCA/1.0/common"><funcCode>ERROR</funcCode><errorCode>OPERATION_FAILED</errorCode></GeneralExceptionResponse>';

        $response = $this->deserialize($xml);

        $this->assertInstanceOf(GeneralExceptionResponse::class, $response);
        $this->assertSame('OPERATION_FAILED', $response->getErrorCode());
        $this->assertNull($response->getMessage());
        $this->assertSame([], $response->getNotifications());
    }

    public function testTheNamespacePrefixIsResolvedFromTheDeclaration(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><ns5:GeneralExceptionResponse xmlns:ns5="http://schemas.nav.gov.hu/NTCA/1.0/common"><ns5:funcCode>ERROR</ns5:funcCode><ns5:errorCode>INVALID_REQUEST</ns5:errorCode><ns5:message>x</ns5:message><ns5:notifications><ns5:notification><ns5:notificationCode>SCHEMA_VIOLATION</ns5:notificationCode><ns5:notificationText>prefixed</ns5:notificationText></ns5:notification></ns5:notifications></ns5:GeneralExceptionResponse>';

        $response = $this->deserialize($xml);

        $this->assertInstanceOf(GeneralExceptionResponse::class, $response);
        $this->assertSame('INVALID_REQUEST', $response->getErrorCode());
        $this->assertSame([['code' => 'SCHEMA_VIOLATION', 'text' => 'prefixed']], $response->getNotifications());
    }

    public function testGeneralErrorResponseStillReadsTheResultElement(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?><GeneralErrorResponse xmlns="http://schemas.nav.gov.hu/OSA/3.0/api" xmlns:common="http://schemas.nav.gov.hu/NTCA/1.0/common"><common:header><common:requestId>R1</common:requestId></common:header><common:result><common:funcCode>ERROR</common:funcCode><common:errorCode>INVALID_SECURITY_USER</common:errorCode><common:message>Helytelen authentikációs adatok!</common:message></common:result><technicalValidationMessages><validationResultCode>ERROR</validationResultCode><validationErrorCode>X1</validationErrorCode><message>first</message></technicalValidationMessages><technicalValidationMessages><validationResultCode>ERROR</validationResultCode><validationErrorCode>X2</validationErrorCode><message>second</message></technicalValidationMessages></GeneralErrorResponse>';

        $response = $this->deserialize($xml);

        $this->assertInstanceOf(GeneralErrorResponse::class, $response);
        $this->assertSame('ERROR', $response->getFunctionCode());
        $this->assertSame('INVALID_SECURITY_USER', $response->getErrorCode());
        $this->assertSame('Helytelen authentikációs adatok!', $response->getMessage());
        $this->assertCount(2, $response->getTechnicalValidationMessages());
        $this->assertSame('X2', $response->getTechnicalValidationMessages()[1]['validationErrorCode']);
    }

    public function testGeneralErrorResponseWithASingleTechnicalValidationMessage(): void
    {
        $data = [
            'result' => ['funcCode' => 'ERROR', 'errorCode' => 'SCHEMA_VIOLATION', 'message' => 'x'],
            'technicalValidationMessages' => ['validationResultCode' => 'ERROR', 'validationErrorCode' => 'X1', 'message' => 'only'],
        ];

        $response = (new ErrorResponseDenormalizer())->denormalize($data, GeneralErrorResponse::class, 'xml');

        $this->assertInstanceOf(GeneralErrorResponse::class, $response);
        $this->assertCount(1, $response->getTechnicalValidationMessages());
        $this->assertSame('X1', $response->getTechnicalValidationMessages()[0]['validationErrorCode']);
    }
}
