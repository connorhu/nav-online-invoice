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

        $invoiceItem = new InvoiceItem();
        $invoiceItem->setAdvanceIndicator(false);
        yield [$invoiceItem, [
            'lineNumber' => null,
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

        $defaultEmptyArray = [
            'lineNumber' => null,
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
        ];

        $invoiceItem = new InvoiceItem();
        $invoiceItem->setAdvanceOriginalInvoice('abc-123');
        yield [$invoiceItem, $defaultEmptyArray];

        $invoiceItem = new InvoiceItem();
        $invoiceItem->setAdvancePaymentDate(new \DateTimeImmutable('2024-01-01'));
        yield [$invoiceItem, $defaultEmptyArray];

        $invoiceItem = new InvoiceItem();
        $invoiceItem->setAdvanceExchangeRate('123.45');
        yield [$invoiceItem, $defaultEmptyArray];

        $invoiceItem = new InvoiceItem();
        $invoiceItem->setAdvanceOriginalInvoice('abc-123');
        $invoiceItem->setAdvancePaymentDate(new \DateTimeImmutable('2024-01-01'));
        $invoiceItem->setAdvanceExchangeRate('123.45');
        yield [$invoiceItem, [
            'lineNumber' => null,
            'advanceData' => [
                'advancePaymentData' => [
                    'advancePaymentDate' => '2024-01-01',
                    'advanceOriginalInvoice' => 'abc-123',
                    'advanceExchangeRate' => '123.45',
                ],
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
