<?php

namespace NAV\OnlineInvoice\Tests\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Enums\HeaderVersionEnum;
use NAV\OnlineInvoice\Http\Enums\RequestVersionEnum;
use NAV\OnlineInvoice\Http\Response\QueryTransactionStatusResponse;
use NAV\OnlineInvoice\Model\Enums\InvoiceStatusEnum;
use NAV\OnlineInvoice\Model\Enums\ValidationResultCodeEnum;
use NAV\OnlineInvoice\Model\Invoice;
use NAV\OnlineInvoice\Http\Response\QueryInvoiceDataResponse;
use NAV\OnlineInvoice\Serializer\Normalizers\AuditDenormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\HeaderNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\QueryInvoiceDataResponseDenormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\QueryTransactionStatusResponseDenormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class QueryTransactionStatusResponseDenormalizerTest extends TestCase
{
    public function testEmptyProcessingResults()
    {
        $denormalizer = new QueryTransactionStatusResponseDenormalizer();

        $data = json_decode(file_get_contents(realpath(__DIR__.'/../../Fixtures/query_transaction_status_response_denormalizer_test.json')), true);
        $type = QueryTransactionStatusResponseDenormalizer::class;
        $format = 'xml';

        $denormalized = $denormalizer->denormalize($data, $type, $format);

        $this->assertInstanceOf(QueryTransactionStatusResponse::class, $denormalized);
        $this->assertSame(RequestVersionEnum::v30, $denormalized->getOriginalRequestVersion());
        $this->assertSame([], $denormalized->getProcessingResults());
    }

    public function testFullProcessingResults()
    {
        $denormalizer = new QueryTransactionStatusResponseDenormalizer();

        $data = json_decode(file_get_contents(realpath(__DIR__.'/../../Fixtures/query_transaction_status_response_denormalizer_test2.json')), true);
        $type = QueryTransactionStatusResponseDenormalizer::class;
        $format = 'xml';

        $denormalized = $denormalizer->denormalize($data, $type, $format);

        $this->assertInstanceOf(QueryTransactionStatusResponse::class, $denormalized);
        $this->assertSame(RequestVersionEnum::v30, $denormalized->getOriginalRequestVersion());
        $this->assertCount(1, $processingResults = $denormalized->getProcessingResults());
        $this->assertSame(1, $processingResults[0]->index);
        $this->assertSame(2, $processingResults[0]->batchIndex);
        $this->assertSame(true, $processingResults[0]->compressedContent);
        $this->assertSame(InvoiceStatusEnum::Done, $processingResults[0]->status);

        $this->assertCount(1, $processingResults[0]->technicalValidationMessages);
        $this->assertSame(ValidationResultCodeEnum::Critical, $processingResults[0]->technicalValidationMessages[0]->resultCode);
        $this->assertSame('1234', $processingResults[0]->technicalValidationMessages[0]->errorCode);
        $this->assertSame('Technical Validation Message', $processingResults[0]->technicalValidationMessages[0]->message);

        $this->assertCount(1, $processingResults[0]->businessValidationMessages);
        $this->assertSame(ValidationResultCodeEnum::Info, $processingResults[0]->businessValidationMessages[0]->resultCode);
        $this->assertSame('5678', $processingResults[0]->businessValidationMessages[0]->errorCode);
        $this->assertSame('Business Validation Message', $processingResults[0]->businessValidationMessages[0]->message);
        $this->assertSame('line', $processingResults[0]->businessValidationMessages[0]->pointerLine);
        $this->assertSame('value', $processingResults[0]->businessValidationMessages[0]->pointerValue);
        $this->assertSame('tag', $processingResults[0]->businessValidationMessages[0]->pointerTag);
        $this->assertSame('invoice number', $processingResults[0]->businessValidationMessages[0]->pointerOriginalInvoiceNumber);
    }

    /**
     * With a single technicalValidationMessages element NAV does not send a list: the XmlEncoder
     * returns the fields of the message itself. Validation message fields also live in the common
     * namespace, not in the api one.
     */
    public function testSingleTechnicalValidationMessage()
    {
        $denormalizer = new QueryTransactionStatusResponseDenormalizer();

        $data = json_decode(file_get_contents(realpath(__DIR__.'/../../Fixtures/query_transaction_status_response_denormalizer_test3.json')), true);
        $type = QueryTransactionStatusResponseDenormalizer::class;
        $format = 'xml';

        $denormalized = $denormalizer->denormalize($data, $type, $format);

        $this->assertCount(3, $processingResults = $denormalized->getProcessingResults());

        $this->assertSame(InvoiceStatusEnum::Done, $processingResults[0]->status);
        $this->assertSame([], $processingResults[0]->technicalValidationMessages);

        $this->assertSame(InvoiceStatusEnum::Aborted, $processingResults[1]->status);
        $this->assertCount(1, $processingResults[1]->technicalValidationMessages);
        $this->assertSame(ValidationResultCodeEnum::Error, $processingResults[1]->technicalValidationMessages[0]->resultCode);
        $this->assertSame('DUPLICATE_IN_REQUEST', $processingResults[1]->technicalValidationMessages[0]->errorCode);
        $this->assertSame('A megadott számlaszám egynél többször szerepel a kérésben.', $processingResults[1]->technicalValidationMessages[0]->message);

        $this->assertCount(2, $processingResults[2]->technicalValidationMessages);
        $this->assertSame(ValidationResultCodeEnum::Error, $processingResults[2]->technicalValidationMessages[1]->resultCode);
        $this->assertSame('DUPLICATE_IN_REQUEST', $processingResults[2]->technicalValidationMessages[1]->errorCode);
    }
}
