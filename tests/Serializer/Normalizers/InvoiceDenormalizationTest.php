<?php

namespace NAV\OnlineInvoice\Tests\Serializer\Normalizers;

use NAV\OnlineInvoice\Model\Enums\UnitOfMeasureEnum;
use NAV\OnlineInvoice\Model\Interfaces\VatRateSummaryInterface;
use NAV\OnlineInvoice\Model\InvoiceItem;
use NAV\OnlineInvoice\Model\VatRateSummary;
use NAV\OnlineInvoice\Serializer\Normalizers\InvoiceItemNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\VatRateNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\VatRateSummaryNormalizer;
use NAV\OnlineInvoice\Tests\Fixtures\AllInOneFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;

/**
 * Denormalization of invoice lines and VAT summaries, as used by InvoiceNormalizer
 * for the invoiceData of a QueryInvoiceData response.
 */
#[CoversClass(VatRateNormalizer::class)]
#[CoversClass(InvoiceItemNormalizer::class)]
#[CoversClass(VatRateSummaryNormalizer::class)]
class InvoiceDenormalizationTest extends TestCase
{
    private Serializer $serializer;

    protected function setUp(): void
    {
        $vatRateNormalizer = new VatRateNormalizer(new AllInOneFactory());

        // VatRateNormalizer also supports every class implementing VatRateInterface
        // (InvoiceItem included), so it has to come after the more specific ones
        $this->serializer = new Serializer([
            new InvoiceItemNormalizer($vatRateNormalizer),
            new VatRateSummaryNormalizer($vatRateNormalizer),
            $vatRateNormalizer,
        ]);
    }

    private static function decode(string $xml): array
    {
        return (new XmlEncoder())->decode($xml, 'xml');
    }

    public function testInvoiceLine(): void
    {
        $data = self::decode(<<<'XML'
<line xmlns="http://schemas.nav.gov.hu/OSA/3.0/data">
    <lineNumber>1</lineNumber>
    <lineExpressionIndicator>true</lineExpressionIndicator>
    <lineDescription>Consulting</lineDescription>
    <quantity>2</quantity>
    <unitOfMeasure>HOUR</unitOfMeasure>
    <unitPrice>100.5</unitPrice>
    <lineAmountsNormal>
        <lineNetAmountData>
            <lineNetAmount>201</lineNetAmount>
            <lineNetAmountHUF>78390</lineNetAmountHUF>
        </lineNetAmountData>
        <lineVatRate>
            <vatPercentage>0.27</vatPercentage>
        </lineVatRate>
        <lineVatData>
            <lineVatAmount>54.27</lineVatAmount>
            <lineVatAmountHUF>21165.3</lineVatAmountHUF>
        </lineVatData>
        <lineGrossAmountData>
            <lineGrossAmountNormal>255.27</lineGrossAmountNormal>
            <lineGrossAmountNormalHUF>99555.3</lineGrossAmountNormalHUF>
        </lineGrossAmountData>
    </lineAmountsNormal>
    <intermediatedService>true</intermediatedService>
</line>
XML);

        $item = $this->serializer->denormalize($data, InvoiceItem::class, 'xml', [
            InvoiceItemNormalizer::XMLNS_CONTEXT_KEY => '',
        ]);

        $this->assertInstanceOf(InvoiceItem::class, $item);
        $this->assertSame(0.27, $item->getVatRatePercentage());
        $this->assertSame('1', $item->getItemNumber());
        $this->assertTrue($item->getLineExpressionIndicator());
        $this->assertSame('Consulting', $item->getLineDescription());
        $this->assertSame('2', $item->getQuantity());
        $this->assertSame(UnitOfMeasureEnum::Hour, $item->getUnitOfMeasure());
        $this->assertSame('100.5', $item->getUnitPrice());
        $this->assertTrue($item->getIntermediatedService());
        $this->assertSame('201', $item->getNetAmount());
        $this->assertSame('78390', $item->getNetAmountHUF());
        $this->assertSame('54.27', $item->getVatAmount());
        $this->assertSame('21165.3', $item->getVatAmountHUF());
        $this->assertSame('255.27', $item->getGrossAmountNormal());
        $this->assertSame('99555.3', $item->getGrossAmountNormalHUF());
    }

    public function testMinimalInvoiceLine(): void
    {
        $data = self::decode(<<<'XML'
<line xmlns="http://schemas.nav.gov.hu/OSA/3.0/data">
    <lineNumber>2</lineNumber>
    <lineExpressionIndicator>false</lineExpressionIndicator>
    <lineAmountsNormal>
        <lineNetAmountData>
            <lineNetAmount>10</lineNetAmount>
            <lineNetAmountHUF>10</lineNetAmountHUF>
        </lineNetAmountData>
        <lineVatRate>
            <vatPercentage>0.05</vatPercentage>
        </lineVatRate>
        <lineVatData>
            <lineVatAmount>0.5</lineVatAmount>
            <lineVatAmountHUF>0.5</lineVatAmountHUF>
        </lineVatData>
    </lineAmountsNormal>
</line>
XML);

        $item = $this->serializer->denormalize($data, InvoiceItem::class, 'xml', [
            InvoiceItemNormalizer::XMLNS_CONTEXT_KEY => '',
        ]);

        $this->assertFalse($item->getLineExpressionIndicator());
        $this->assertNull($item->getLineDescription());
        $this->assertNull($item->getQuantity());
        $this->assertNull($item->getUnitOfMeasure());
        $this->assertNull($item->getUnitPrice());
        $this->assertNull($item->getIntermediatedService());
        $this->assertSame(0.05, $item->getVatRatePercentage());
    }

    public function testVatRateSummary(): void
    {
        $data = self::decode(<<<'XML'
<summaryByVatRate xmlns="http://schemas.nav.gov.hu/OSA/3.0/data">
    <vatRate>
        <vatPercentage>0.27</vatPercentage>
    </vatRate>
    <vatRateNetData>
        <vatRateNetAmount>100</vatRateNetAmount>
        <vatRateNetAmountHUF>39000</vatRateNetAmountHUF>
    </vatRateNetData>
    <vatRateVatData>
        <vatRateVatAmount>27</vatRateVatAmount>
        <vatRateVatAmountHUF>10530</vatRateVatAmountHUF>
    </vatRateVatData>
    <vatRateGrossData>
        <vatRateGrossAmount>127</vatRateGrossAmount>
        <vatRateGrossAmountHUF>49530</vatRateGrossAmountHUF>
    </vatRateGrossData>
</summaryByVatRate>
XML);

        $summary = $this->serializer->denormalize($data, VatRateSummaryInterface::class, 'xml', [
            VatRateSummaryNormalizer::XMLNS_CONTEXT_KEY => '',
        ]);

        $this->assertInstanceOf(VatRateSummary::class, $summary);
        $this->assertSame(0.27, $summary->getVatRatePercentage());
        $this->assertSame('100', $summary->getNetAmount());
        $this->assertSame('39000', $summary->getNetAmountHUF());
        $this->assertSame('27', $summary->getVatAmount());
        $this->assertSame('10530', $summary->getVatAmountHUF());
        $this->assertSame('127', $summary->getGrossAmount());
        $this->assertSame('49530', $summary->getGrossAmountHUF());
    }

    public function testFactoryIsUsedWithoutItemFactoryInContext(): void
    {
        $factory = new AllInOneFactory();
        $normalizer = new VatRateNormalizer($factory);

        $vatRate = $normalizer->denormalize(['vatPercentage' => '0.18'], VatRateSummaryInterface::class, 'xml', [
            VatRateNormalizer::XMLNS_CONTEXT_KEY => '',
        ]);

        $this->assertSame($factory->vatRate, $vatRate);
        $this->assertSame(0.18, $vatRate->getVatRatePercentage());
    }
}
