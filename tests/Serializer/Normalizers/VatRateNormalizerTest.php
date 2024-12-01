<?php

namespace NAV\OnlineInvoice\Tests\Serializer\Normalizers;

use NAV\OnlineInvoice\Model\Enums\VatRateAmountMismatchCase;
use NAV\OnlineInvoice\Model\Enums\VatRateExemptionCase;
use NAV\OnlineInvoice\Model\Enums\VatRateOutOfScopeCase;
use NAV\OnlineInvoice\Model\Interfaces\VatRateInterface;
use NAV\OnlineInvoice\Serializer\Normalizers\VatRateNormalizer;
use NAV\OnlineInvoice\Tests\Fixtures\AllInOneFactory;
use NAV\OnlineInvoice\Tests\Fixtures\VatRateTraitImplementation;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(VatRateNormalizer::class)]
class VatRateNormalizerTest extends TestCase
{
    private VatRateNormalizer $normalizer;
    private AllInOneFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new AllInOneFactory();
        $this->normalizer = new VatRateNormalizer($this->factory);
    }

    public function testSupport(): void
    {
        $vatRate = new VatRateTraitImplementation();
        $this->assertTrue($this->normalizer->supportsNormalization($vatRate));
    }

    #[DataProvider('fieldsDataProvider')]
    public function testFields(VatRateTraitImplementation $vatRate, array $expected): void
    {
        $this->assertSame($expected, $this->normalizer->normalize($vatRate));
    }

    public static function fieldsDataProvider(): \Generator
    {
        $vatRate = new VatRateTraitImplementation();
        yield [$vatRate, []];

        $vatRate = new VatRateTraitImplementation();
        $vatRate->setVatRatePercentage(2);
        yield [$vatRate, [
            'vatPercentage' => 2.0,
        ]];

        $vatRate = new VatRateTraitImplementation();
        $vatRate->setVatRatePercentage(2.5);
        yield [$vatRate, [
            'vatPercentage' => 2.5,
        ]];

        $vatRate = new VatRateTraitImplementation();
        $vatRate->setVatRateContent(2.5);
        yield [$vatRate, [
            'vatContent' => 2.5,
        ]];

        $vatRate = new VatRateTraitImplementation();
        $vatRate->setVatRateExemptionCase(VatRateExemptionCase::IntraCommunityExemptSupply);
        yield [$vatRate, [
            'vatExemption' => [
                'case' => 'KBAET',
            ],
        ]];

        $vatRate = new VatRateTraitImplementation();
        $vatRate->setVatRateExemptionReason('reason');
        yield [$vatRate, []];

        $vatRate = new VatRateTraitImplementation();
        $vatRate->setVatRateExemptionCase(VatRateExemptionCase::TaxExemptActivity);
        $vatRate->setVatRateExemptionReason('reason of exemption');
        yield [$vatRate, [
            'vatExemption' => [
                'case' => 'TAM',
                'reason' => 'reason of exemption',
            ],
        ]];

        $vatRate = new VatRateTraitImplementation();
        $vatRate->setVatRateOutOfScopeCase(VatRateOutOfScopeCase::OutsideVatScope);
        yield [$vatRate, [
            'vatOutOfScope' => [
                'case' => 'ATK',
            ],
        ]];

        $vatRate = new VatRateTraitImplementation();
        $vatRate->setVatRateOutOfScopeReason('reason of out of scope');
        yield [$vatRate, []];

        $vatRate = new VatRateTraitImplementation();
        $vatRate->setVatRateOutOfScopeCase(VatRateOutOfScopeCase::StateMemberReverseCharge);
        $vatRate->setVatRateOutOfScopeReason('reason of out of scope');
        yield [$vatRate, [
            'vatOutOfScope' => [
                'case' => 'EUFADE',
                'reason' => 'reason of out of scope',
            ],
        ]];

        $vatRate = new VatRateTraitImplementation();
        $vatRate->setVatRateDomesticReverseCharge(false);
        yield [$vatRate, [
            'vatDomesticReverseCharge' => false,
        ]];

        $vatRate = new VatRateTraitImplementation();
        $vatRate->setVatRateMarginSchemeIndicator(false);
        yield [$vatRate, [
            'marginSchemeIndicator' => false,
        ]];

        $vatRate = new VatRateTraitImplementation();
        $vatRate->setVatRateAmountMismatchCase(VatRateAmountMismatchCase::RefundableVat);
        yield [$vatRate, []];

        $vatRate = new VatRateTraitImplementation();
        $vatRate->setVatRateAmountMismatchRate(4.0);
        yield [$vatRate, []];

        $vatRate = new VatRateTraitImplementation();
        $vatRate->setVatRateAmountMismatchCase(VatRateAmountMismatchCase::RefundableVat);
        $vatRate->setVatRateAmountMismatchRate(4.0);
        yield [$vatRate, [
            'vatAmountMismatch' => [
                'vatRate' => 4.0,
                'case' => 'REFUNDABLE_VAT',
            ],
        ]];

        $vatRate = new VatRateTraitImplementation();
        $vatRate->setVatRateNoVatCharge(false);
        yield [$vatRate, [
            'noVatCharge' => false,
        ]];
    }

    public function testDenormalizeMissingXmlNsContextKey(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Context key missing: "_vat_rate_xmlns"');
        $this->normalizer->denormalize([], '');
    }

    public function testDenormalizeEmpty(): void
    {
        $data = [];

        $result = $this->normalizer->denormalize($data, '', context: [
            VatRateNormalizer::XMLNS_CONTEXT_KEY => '_xml_ns_prefix::',
        ]);

        $this->assertInstanceOf(VatRateInterface::class, $result);
        $this->assertSame(null, $result->getVatRatePercentage());
        $this->assertSame(null, $result->getVatRateContent());
        $this->assertSame(null, $result->getVatRateExemptionCase());
        $this->assertSame(null, $result->getVatRateExemptionReason());
        $this->assertSame(null, $result->getVatRateOutOfScopeCase());
        $this->assertSame(null, $result->getVatRateOutOfScopeReason());
        $this->assertSame(null, $result->getVatRateDomesticReverseCharge());
        $this->assertSame(null, $result->getVatRateMarginSchemeIndicator());
        $this->assertSame(null, $result->getVatRateAmountMismatchRate());
        $this->assertSame(null, $result->getVatRateAmountMismatchCase());
        $this->assertSame(null, $result->getVatRateNoVatCharge());
    }

    public function testDenormalizeAllFields(): void
    {
        $data = [
            '_xml_ns_prefix:vatPercentage' => 2.5,
            '_xml_ns_prefix:vatContent' => 1.5,
            '_xml_ns_prefix:vatExemption' => [
                '_xml_ns_prefix:case' => 'TAM',
                '_xml_ns_prefix:reason' => 'vatExemption reason',
            ],
            '_xml_ns_prefix:vatOutOfScope' => [
                '_xml_ns_prefix:case' => 'EUFAD37',
                '_xml_ns_prefix:reason' => 'vatOutOfScope reason',
            ],
            '_xml_ns_prefix:vatDomesticReverseCharge' => false,
            '_xml_ns_prefix:marginSchemeIndicator' => false,
            '_xml_ns_prefix:vatAmountMismatch' => [
                '_xml_ns_prefix:vatRate' => 10.5,
                '_xml_ns_prefix:case' => 'NONREFUNDABLE_VAT',
            ],
            '_xml_ns_prefix:noVatCharge' => false,
        ];

        $result = $this->normalizer->denormalize($data, '', context: [
            VatRateNormalizer::XMLNS_CONTEXT_KEY => '_xml_ns_prefix',
        ]);

        $this->assertInstanceOf(VatRateInterface::class, $result);
        $this->assertSame(2.5, $result->getVatRatePercentage());
        $this->assertSame(1.5, $result->getVatRateContent());
        $this->assertSame(VatRateExemptionCase::TaxExemptActivity, $result->getVatRateExemptionCase());
        $this->assertSame('vatExemption reason', $result->getVatRateExemptionReason());
        $this->assertSame(VatRateOutOfScopeCase::StateMemberReverseChargeSec37, $result->getVatRateOutOfScopeCase());
        $this->assertSame('vatOutOfScope reason', $result->getVatRateOutOfScopeReason());
        $this->assertSame(false, $result->getVatRateDomesticReverseCharge());
        $this->assertSame(false, $result->getVatRateMarginSchemeIndicator());
        $this->assertSame(10.5, $result->getVatRateAmountMismatchRate());
        $this->assertSame(VatRateAmountMismatchCase::NonRefundableVat, $result->getVatRateAmountMismatchCase());
        $this->assertSame(false, $result->getVatRateNoVatCharge());
    }
}
