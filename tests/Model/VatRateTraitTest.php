<?php

namespace NAV\OnlineInvoice\Tests\Model;

use NAV\OnlineInvoice\Model\Enums\VatRateExemptionCase;
use NAV\OnlineInvoice\Model\Enums\VatRateOutOfScopeCase;
use NAV\OnlineInvoice\Model\Interfaces\VatRateInterface;
use NAV\OnlineInvoice\Tests\Fixtures\VatRateTraitImplementation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \NAV\OnlineInvoice\Model\Interfaces\VatRateInterface
 * @covers \NAV\OnlineInvoice\Model\Traits\VatRateTrait
 */
class VatRateTraitTest extends TestCase
{
    /**
     * @dataProvider vatRateExemptionCaseStringDataProvider
     */
    public function testVatRateExemptionCaseString(VatRateInterface $object, ?string $stringValue)
    {
        $this->assertSame($stringValue, $object->getVatRateExemptionCase()?->toString());
    }

    public static function vatRateExemptionCaseStringDataProvider(): \Generator
    {
        $object = new VatRateTraitImplementation();
        $object->setVatRateExemptionCase(null);
        yield [$object, null];

        $object = new VatRateTraitImplementation();
        $object->setVatRateExemptionCase(VatRateExemptionCase::initWithString('AAM'));
        yield [$object, 'AAM'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateExemptionCase(VatRateExemptionCase::initWithString('TAM'));
        yield [$object, 'TAM'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateExemptionCase(VatRateExemptionCase::initWithString('KBAET'));
        yield [$object, 'KBAET'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateExemptionCase(VatRateExemptionCase::initWithString('KBAUK'));
        yield [$object, 'KBAUK'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateExemptionCase(VatRateExemptionCase::initWithString('EAM'));
        yield [$object, 'EAM'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateExemptionCase(VatRateExemptionCase::initWithString('NAM'));
        yield [$object, 'NAM'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateExemptionCase(VatRateExemptionCase::initWithString('UNKNOWN'));
        yield [$object, 'UNKNOWN'];
    }

    /**
     * @dataProvider vatRateOutOfScopeCaseStringDataProvider
     */
    public function testVatRateOutOfScopeCaseString(VatRateInterface $object, ?string $stringValue)
    {
        $this->assertSame($stringValue, $object->getVatRateOutOfScopeCase()?->toString());
    }

    public static function vatRateOutOfScopeCaseStringDataProvider(): \Generator
    {
        $object = new VatRateTraitImplementation();
        $object->setVatRateOutOfScopeCase(null);
        yield [$object, null];

        $object = new VatRateTraitImplementation();
        $object->setVatRateOutOfScopeCase(VatRateOutOfScopeCase::initWithString('ATK'));
        yield [$object, 'ATK'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateOutOfScopeCase(VatRateOutOfScopeCase::initWithString('EUFAD37'));
        yield [$object, 'EUFAD37'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateOutOfScopeCase(VatRateOutOfScopeCase::initWithString('EUFADE'));
        yield [$object, 'EUFADE'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateOutOfScopeCase(VatRateOutOfScopeCase::initWithString('EUE'));
        yield [$object, 'EUE'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateOutOfScopeCase(VatRateOutOfScopeCase::initWithString('HO'));
        yield [$object, 'HO'];

        $object = new VatRateTraitImplementation();
        $object->setVatRateOutOfScopeCase(VatRateOutOfScopeCase::initWithString('UNKNOWN'));
        yield [$object, 'UNKNOWN'];
    }

    /**
     * @dataProvider optionalFieldsDataProvider
     */
    public function testOptionalFields(VatRateInterface $object, string $getter): void
    {
        $this->assertNull($object->{$getter}());
    }

    public static function optionalFieldsDataProvider(): \Generator
    {
        $object = new VatRateTraitImplementation();

        yield [$object, 'getVatRatePercentage'];
        yield [$object, 'getVatRateContent'];
        yield [$object, 'getVatRateExemptionCase'];
        yield [$object, 'getVatRateExemptionReason'];
        yield [$object, 'getVatRateOutOfScopeCase'];
        yield [$object, 'getVatRateOutOfScopeReason'];
        yield [$object, 'getVatRateDomesticReverseCharge'];
        yield [$object, 'getVatRateMarginSchemeIndicator'];
        yield [$object, 'getVatRateAmountMismatchRate'];
        yield [$object, 'getVatRateAmountMismatchCase'];
        yield [$object, 'getVatRateNoVatCharge'];
    }
}

