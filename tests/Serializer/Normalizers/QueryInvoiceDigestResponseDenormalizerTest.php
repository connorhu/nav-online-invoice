<?php

namespace NAV\OnlineInvoice\Tests\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Response\QueryInvoiceDigestResponse;
use NAV\OnlineInvoice\Model\Enums\InvoiceAppearanceEnum;
use NAV\OnlineInvoice\Model\Enums\InvoiceOperationEnum;
use NAV\OnlineInvoice\Model\Enums\PaymentMethodEnum;
use NAV\OnlineInvoice\Providers\ResponseClassProvider;
use NAV\OnlineInvoice\Serializer\Encoder\RequestEncoder;
use NAV\OnlineInvoice\Serializer\Normalizers\QueryInvoiceDigestResponseDenormalizer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Serializer;

/**
 * The fixtures validate against the NAV invoiceApi.xsd v3.0 (QueryInvoiceDigestResponse).
 */
#[CoversClass(QueryInvoiceDigestResponseDenormalizer::class)]
class QueryInvoiceDigestResponseDenormalizerTest extends TestCase
{
    private function deserialize(string $fixture): QueryInvoiceDigestResponse
    {
        $xml = file_get_contents(__DIR__.'/../../Fixtures/'.$fixture);

        // same path as OnlineInvoiceRestClient::sendRequest()
        $serializer = new Serializer([new QueryInvoiceDigestResponseDenormalizer()], [new RequestEncoder()]);
        $response = $serializer->deserialize($xml, ResponseClassProvider::getResponseClass($xml), 'request');

        $this->assertInstanceOf(QueryInvoiceDigestResponse::class, $response);

        return $response;
    }

    public function testMultipleDigests(): void
    {
        $response = $this->deserialize('query_invoice_digest_response.xml');

        $this->assertSame(1, $response->getCurrentPage());
        $this->assertSame(3, $response->getAvailablePage());
        $this->assertCount(2, $digests = $response->getInvoiceDigests());

        $first = $digests[0];
        $this->assertSame('INV/2024/0001', $first->invoiceNumber);
        $this->assertNull($first->batchIndex);
        $this->assertSame(InvoiceOperationEnum::Create, $first->invoiceOperation);
        $this->assertSame('NORMAL', $first->invoiceCategory);
        $this->assertSame('2024-01-15', $first->invoiceIssueDate->format('Y-m-d'));
        $this->assertSame('12345678', $first->supplierTaxNumber);
        $this->assertNull($first->supplierGroupMemberTaxNumber);
        $this->assertSame('Supplier Ltd.', $first->supplierName);
        $this->assertSame('87654321', $first->customerTaxNumber);
        $this->assertSame('Customer Ltd.', $first->customerName);
        $this->assertSame(PaymentMethodEnum::Transfer, $first->paymentMethod);
        $this->assertSame('2024-01-30', $first->paymentDate->format('Y-m-d'));
        $this->assertSame(InvoiceAppearanceEnum::Electronic, $first->invoiceAppearance);
        $this->assertSame('OSZ', $first->source);
        $this->assertSame('2024-01-15', $first->invoiceDeliveryDate->format('Y-m-d'));
        $this->assertSame('HUF', $first->currency);
        $this->assertSame(20000.0, $first->invoiceNetAmount);
        $this->assertSame(20000.0, $first->invoiceNetAmountHUF);
        $this->assertSame(5400.0, $first->invoiceVatAmount);
        $this->assertSame(5400.0, $first->invoiceVatAmountHUF);
        $this->assertSame('4ABCDEF123456789', $first->transactionId);
        $this->assertSame(1, $first->index);
        $this->assertNull($first->originalInvoiceNumber);
        $this->assertNull($first->modificationIndex);
        $this->assertSame('2024-01-15T09:30:00.000+00:00', $first->insDate->format('Y-m-d\TH:i:s.vP'));
        $this->assertFalse($first->completenessIndicator);

        $second = $digests[1];
        $this->assertSame('INV/2024/0002', $second->invoiceNumber);
        $this->assertSame(2, $second->batchIndex);
        $this->assertSame(InvoiceOperationEnum::Modify, $second->invoiceOperation);
        $this->assertSame('SIMPLIFIED', $second->invoiceCategory);
        $this->assertSame('11111111', $second->supplierGroupMemberTaxNumber);
        $this->assertNull($second->customerTaxNumber);
        $this->assertNull($second->customerName);
        $this->assertNull($second->paymentMethod);
        $this->assertNull($second->paymentDate);
        $this->assertSame(InvoiceAppearanceEnum::Paper, $second->invoiceAppearance);
        $this->assertSame('EUR', $second->currency);
        $this->assertSame(-12.5, $second->invoiceNetAmount);
        $this->assertSame(-4875.25, $second->invoiceNetAmountHUF);
        $this->assertNull($second->invoiceVatAmount);
        $this->assertNull($second->transactionId);
        $this->assertSame('INV/2024/0001', $second->originalInvoiceNumber);
        $this->assertSame(1, $second->modificationIndex);
        $this->assertSame('2024-01-20T11:45:10.500+00:00', $second->insDate->format('Y-m-d\TH:i:s.vP'));
        $this->assertNull($second->completenessIndicator);
    }

    /**
     * A single invoiceDigest is decoded by the XmlEncoder into its fields, not into a list.
     * This fixture also uses the api namespace as the default namespace.
     */
    public function testSingleDigest(): void
    {
        $response = $this->deserialize('query_invoice_digest_response_single.xml');

        $this->assertSame(1, $response->getAvailablePage());
        $this->assertCount(1, $digests = $response->getInvoiceDigests());
        $this->assertSame('INV/2024/0001', $digests[0]->invoiceNumber);
        $this->assertSame(InvoiceOperationEnum::Create, $digests[0]->invoiceOperation);
        $this->assertSame(5400.0, $digests[0]->invoiceVatAmountHUF);
    }

    public function testNoDigest(): void
    {
        $response = $this->deserialize('query_invoice_digest_response_empty.xml');

        $this->assertSame(1, $response->getCurrentPage());
        $this->assertSame(0, $response->getAvailablePage());
        $this->assertSame([], $response->getInvoiceDigests());
    }
}
