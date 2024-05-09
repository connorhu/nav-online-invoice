<?php

namespace NAV\OnlineInvoice\Tests\Serializer\Normalizers;

use NAV\OnlineInvoice\Model\Enums\VatRateAmountMismatchCase;
use NAV\OnlineInvoice\Model\Enums\VatRateExemptionCase;
use NAV\OnlineInvoice\Model\Enums\VatRateOutOfScopeCase;
use NAV\OnlineInvoice\Serializer\Normalizers\VatRateNormalizer;
use NAV\OnlineInvoice\Tests\Fixtures\VatRateTraitImplementation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \NAV\OnlineInvoice\Serializer\Normalizers\VatRateNormalizer
 */
class VatRateNormalizerTest extends TestCase
{
    private VatRateNormalizer $normalizer;

    protected function setUp(): void
    {
        $this->normalizer = new VatRateNormalizer();
    }

    public function testSupport(): void
    {
        $vatRate = new VatRateTraitImplementation();
        $this->assertTrue($this->normalizer->supportsNormalization($vatRate));
    }

    /**
     * @dataProvider fieldsDataProvider
     */
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
}
