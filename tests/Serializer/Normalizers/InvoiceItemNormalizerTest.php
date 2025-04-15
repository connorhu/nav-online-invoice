<?php

namespace NAV\OnlineInvoice\Tests\Serializer\Normalizers;

use NAV\OnlineInvoice\Model\InvoiceItem;
use NAV\OnlineInvoice\Serializer\Normalizers\InvoiceItemNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\VatRateNormalizer;
use NAV\OnlineInvoice\Tests\Fixtures\AllInOneFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(VatRateNormalizer::class)]
class InvoiceItemNormalizerTest extends TestCase
{
    private InvoiceItemNormalizer $normalizer;
    private VatRateNormalizer $vatRateNormalizer;
    private AllInOneFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new AllInOneFactory();
        $this->vatRateNormalizer = new VatRateNormalizer($this->factory);
        $this->normalizer = new InvoiceItemNormalizer($this->vatRateNormalizer);
    }

    public function testSupport(): void
    {
        $invoiceItem = new InvoiceItem();
        $this->assertTrue($this->normalizer->supportsNormalization($invoiceItem));
    }

    #[DataProvider('advanceIndicatorFieldsDataProvider')]
    public function testFields(InvoiceItem $invoiceItem, array $expected): void
    {
        $this->assertSame($expected, $this->normalizer->normalize($invoiceItem));
    }

    public static function advanceIndicatorFieldsDataProvider(): \Generator
    {
        $invoiceItem = new InvoiceItem();
        $invoiceItem->setAdvanceIndicator(true);
        yield [$invoiceItem, [
            'lineNumber' => null,
            'advanceData' => [
                'advanceIndicator' => 'true',
            ],
            'lineAmountsNormal' => [
                'lineNetAmountData' => [
                    'lineNetAmount' => null,
                    'lineNetAmountHUF' => null,
                ],
                'lineVatRate' => [],
                'lineVatData' => [
                    'lineVatAmount' => null,
                    'lineVatAmountHUF' => null,
                ],
            ],
        ]];
    }
}
