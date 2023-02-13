<?php

namespace NAV\OnlineInvoice\Tests\Entity;

use NAV\OnlineInvoice\Entity\Interfaces\VatRateInterface;
use NAV\OnlineInvoice\Tests\Fixtures\VatRateTraitImplementation;
use PHPUnit\Framework\TestCase;

class VatRateTraitTest extends TestCase
{
    /**
     * @dataProvider vatRateExemptionCaseStringDataProvider
     */
    public function testVatRateExemptionCaseString(VatRateInterface $object, ?string $stringValue)
    {
        $this->assertSame($stringValue, $object->getVatRateExemptionCaseString());
    }

    public function vatRateExemptionCaseStringDataProvider(): \Generator
    {
        $object = new VatRateTraitImplementation();
        $object->setVatRateExemptionCase(null);
        yield [$object, null];

        $object = new VatRateTraitImplementation();
        $object->setVatRateExemptionCase(VatRateInterface::VAT_RATE_EXEMPTION_CASE_AAM);
        yield [$object, 'AAM'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateExemptionCase(VatRateInterface::VAT_RATE_EXEMPTION_CASE_TAM);
        yield [$object, 'TAM'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateExemptionCase(VatRateInterface::VAT_RATE_EXEMPTION_CASE_KBAET);
        yield [$object, 'KBAET'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateExemptionCase(VatRateInterface::VAT_RATE_EXEMPTION_CASE_KBAUK);
        yield [$object, 'KBAUK'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateExemptionCase(VatRateInterface::VAT_RATE_EXEMPTION_CASE_EAM);
        yield [$object, 'EAM'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateExemptionCase(VatRateInterface::VAT_RATE_EXEMPTION_CASE_NAM);
        yield [$object, 'NAM'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateExemptionCase(VatRateInterface::VAT_RATE_EXEMPTION_CASE_UNKNOWN);
        yield [$object, 'UNKNOWN'];
    }

    /**
     * @dataProvider vatRateOutOfScopeCaseStringDataProvider
     */
    public function testVatRateOutOfScopeCaseString(VatRateInterface $object, ?string $stringValue)
    {
        $this->assertSame($stringValue, $object->getVatRateOutOfScopeCaseString());
    }

    public function vatRateOutOfScopeCaseStringDataProvider(): \Generator
    {
        $object = new VatRateTraitImplementation();
        $object->setVatRateOutOfScopeCase(null);
        yield [$object, null];

        $object = new VatRateTraitImplementation();
        $object->setVatRateOutOfScopeCase(VatRateInterface::VAT_RATE_OUT_OF_SCOPE_CASE_ATK);
        yield [$object, 'ATK'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateOutOfScopeCase(VatRateInterface::VAT_RATE_OUT_OF_SCOPE_CASE_EUFAD37);
        yield [$object, 'EUFAD37'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateOutOfScopeCase(VatRateInterface::VAT_RATE_OUT_OF_SCOPE_CASE_EUFADE);
        yield [$object, 'EUFADE'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateOutOfScopeCase(VatRateInterface::VAT_RATE_OUT_OF_SCOPE_CASE_EUE);
        yield [$object, 'EUE'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateOutOfScopeCase(VatRateInterface::VAT_RATE_OUT_OF_SCOPE_CASE_HO);
        yield [$object, 'HO'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateOutOfScopeCase(VatRateInterface::VAT_RATE_OUT_OF_SCOPE_CASE_UNKNOWN);
        yield [$object, 'UNKNOWN'];
    }
}

