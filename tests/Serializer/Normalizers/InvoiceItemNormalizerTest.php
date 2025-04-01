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

    #[DataProvider('discountFieldsDataProvider')]
    public function testFields(InvoiceItem $invoiceItem, array $expected): void
    {
        $this->assertSame($expected, $this->normalizer->normalize($invoiceItem));
    }

    public static function discountFieldsDataProvider(): \Generator
    {
        $invoiceItem = new InvoiceItem();
        $invoiceItem->setLineExpressionIndicator(true);
        $invoiceItem->setDiscountDescription('wont normalize');
        yield [$invoiceItem, [
            'lineNumber' => null,
            'lineExpressionIndicator' => 'true',
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

        $invoiceItem = new InvoiceItem();
        $invoiceItem->setLineExpressionIndicator(true);
        $invoiceItem->setDiscountValue(314.96);
        $invoiceItem->setDiscountDescription('discount description');
        yield [$invoiceItem, [
            'lineNumber' => null,
            'lineExpressionIndicator' => 'true',
            'lineDiscountData' => [
                'discountDescription' => 'discount description',
                'discountValue' => 314.96,
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

        $invoiceItem = new InvoiceItem();
        $invoiceItem->setLineExpressionIndicator(true);
        $invoiceItem->setDiscountRate(0.3);
        $invoiceItem->setDiscountDescription('discount description');
        yield [$invoiceItem, [
            'lineNumber' => null,
            'lineExpressionIndicator' => 'true',
            'lineDiscountData' => [
                'discountDescription' => 'discount description',
                'discountRate' => 0.3,
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

        $invoiceItem = new InvoiceItem();
        $invoiceItem->setLineExpressionIndicator(true);
        $invoiceItem->setDiscountValue(314.96);
        $invoiceItem->setDiscountRate(0.3);
        $invoiceItem->setDiscountDescription('discount description');
        yield [$invoiceItem, [
            'lineNumber' => null,
            'lineExpressionIndicator' => 'true',
            'lineDiscountData' => [
                'discountDescription' => 'discount description',
                'discountValue' => 314.96,
                'discountRate' => 0.3,
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
