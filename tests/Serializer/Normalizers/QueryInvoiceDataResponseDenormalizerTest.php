<?php

namespace NAV\OnlineInvoice\Tests\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Enums\HeaderVersionEnum;
use NAV\OnlineInvoice\Model\Invoice;
use NAV\OnlineInvoice\Http\Response\QueryInvoiceDataResponse;
use NAV\OnlineInvoice\Serializer\Normalizers\AuditDenormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\HeaderNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\QueryInvoiceDataResponseDenormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class QueryInvoiceDataResponseDenormalizerTest extends TestCase
{
    public function testDenormalize()
    {
        $normalizers = [
            new HeaderNormalizer(),
            new SoftwareNormalizer(),
            new AuditDenormalizer(),
        ];

        $serializer = new Serializer($normalizers);

        $mock = self::getMockBuilder(SerializerInterface::class)
            ->getMock();

        $mock->expects($this->once())
            ->method('deserialize')
            ->with('invoicedata')
            ->willReturn(new Invoice());

        $denormalizer = new QueryInvoiceDataResponseDenormalizer();
        $denormalizer->setDenormalizer($serializer);
        $denormalizer->setSerializer($mock);

        $data = json_decode(file_get_contents(realpath(__DIR__.'/../../Fixtures/query_invoice_data_response_denormalizer_test.json')), true);
        $type = QueryInvoiceDataResponse::class;
        $format = 'xml';

        $denormalized = $denormalizer->denormalize($data, $type, $format);

        $this->assertSame('2023-01-01T10:10:01.000Z', $denormalized->getHeader()->getTimestamp()->format('Y-m-d\TH:i:s.000\Z'));
        $this->assertSame(HeaderVersionEnum::V1, $denormalized->getHeader()->getHeaderVersion());
        $this->assertSame('1.0', $denormalized->getHeader()->getHeaderVersion()->value);
        $this->assertSame('HUSOFTWARE_ID', $denormalized->getSoftware()->getId());
        $this->assertSame('Very special invoice software', $denormalized->getSoftware()->getName());
        $this->assertSame('ONLINE_SERVICE', $denormalized->getSoftware()->getOperation());
        $this->assertSame('1.0', $denormalized->getSoftware()->getMainVersion());
        $this->assertSame('Software Dev Corp Inc.', $denormalized->getSoftware()->getDevName());
        $this->assertSame('invoicing@corp.tld', $denormalized->getSoftware()->getDevContact());
        $this->assertSame('HU', $denormalized->getSoftware()->getDevCountryCode());
        $this->assertSame('12345678-1-99', $denormalized->getSoftware()->getDevTaxNumber());
        $this->assertSame('2022-12-01T10:00:05.000Z', $denormalized->getAudit()->getInsertDate()->format('Y-m-d\TH:i:s.000\Z'));
        $this->assertSame('userid', $denormalized->getAudit()->getInsertCustomerUser());
        $this->assertSame('MGM', $denormalized->getAudit()->getSource());
        $this->assertSame('TRANSACTIONID', $denormalized->getAudit()->getTransactionId());
        $this->assertSame(1, $denormalized->getAudit()->getIndex());
        $this->assertSame(99, $denormalized->getAudit()->getBatchIndex());
        $this->assertSame('3.0', $denormalized->getAudit()->getOriginalRequestVersion());
    }
}
