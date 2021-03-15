<?php

namespace NAV\Test\OnlineInvoice\Validator;

use NAV\OnlineInvoice\Validator\Constraints\TaxNumber;
use NAV\OnlineInvoice\Validator\Constraints\TaxNumberValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class TaxNumberValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator()
    {
        return new TaxNumberValidator();
    }
    
    public function testNullIsValid()
    {
        $this->validator->validate(null, new TaxNumber());
        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid()
    {
        $this->validator->validate('', new TaxNumber());
        $this->assertNoViolation();
    }
    
    /**
     * @dataProvider getValidTaxNumbers
     */
    public function testValidTaxNumbers($taxNumber)
    {
        $this->validator->validate($taxNumber, new TaxNumber());

        $this->assertNoViolation();
    }

    public function getValidTaxNumbers()
    {
        return [
            ['12345678102'],
            ['123456781'],
            ['12345678'],
        ];
    }
    
    public function testExpectsStringCompatibleType()
    {
        $this->expectException('Symfony\Component\Validator\Exception\UnexpectedValueException');
        $this->validator->validate(new \stdClass(), new TaxNumber());
        $this->validator->validate(11111111111, new TaxNumber());
    }
    
    public function testInvalidTaxNumberLength()
    {
        $taxNumber = '111111111111';
        $constraint = new TaxNumber([
            'messageLength' => 'myMessage',
        ]);

        $this->validator->validate($taxNumber, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', $taxNumber)
            ->setCode(TaxNumber::INVALID_LENGTH_ERROR)
            ->assertRaised();
    }
    
    /**
     * @dataProvider getInvalidTaxPayerNumbers
     */
    public function testInvalidTaxPayerIdFormats($taxNumber)
    {
        $constraint = new TaxNumber([
            'messageTaxpayerId' => 'myMessage',
        ]);

        $this->validator->validate($taxNumber, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', $taxNumber)
            ->setCode(TaxNumber::INVALID_TAXPAYER_FORMAT_ERROR)
            ->assertRaised();
    }

    public function getInvalidTaxPayerNumbers()
    {
        return [
            ['1111'],
            ['abc214'],
        ];
    }
    
    /**
     * @dataProvider getInvalidVatCodes
     */
    public function testInvalidVatCodes($taxNumber)
    {
        $constraint = new TaxNumber([
            'messageVatCode' => 'myMessage',
        ]);

        $this->validator->validate($taxNumber, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', $taxNumber)
            ->setCode(TaxNumber::INVALID_VATCODE_FORMAT_ERROR)
            ->assertRaised();
    }

    public function getInvalidVatCodes()
    {
        return [
            ['123456786'],
            ['12345678a'],
            ['12345678.'],
        ];
    }
    
    /**
     * @dataProvider getInvalidCountryCodes
     */
    public function testInvalidCountryCodes($taxNumber)
    {
        $constraint = new TaxNumber([
            'messageCountryCode' => 'myMessage',
        ]);

        $this->validator->validate($taxNumber, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', $taxNumber)
            ->setCode(TaxNumber::INVALID_COUNTRYCODE_FORMAT_ERROR)
            ->assertRaised();
    }

    public function getInvalidCountryCodes()
    {
        return [
            ['12345678100'],
            ['123456781bb'],
            ['123456781..'],
        ];
    }
}