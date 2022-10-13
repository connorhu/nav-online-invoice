<?php

namespace NAV\Tests\OnlineInvoice\Validator;

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
    public function testInvalidTaxPayerIdFormats(string $taxNumber)
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

    public function getInvalidTaxPayerNumbers(): \Generator
    {
        yield ['1111'];
        yield ['abc214'];
    }
    
    /**
     * @dataProvider getInvalidVatCodes
     */
    public function testInvalidVatCodes(string $taxNumber, string $expectedVatCode)
    {
        $constraint = new TaxNumber([
            'messageVatCodeInvalid' => 'myMessage',
        ]);

        $this->validator->validate($taxNumber, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', $taxNumber)
            ->setParameter('{{ vat_code }}', $expectedVatCode)
            ->setCode(TaxNumber::INVALID_VAT_CODE_FORMAT_ERROR)
            ->assertRaised();
    }

    public function getInvalidVatCodes(): \Generator
    {
        yield ['123456786', '6'];
        yield ['12345678a', 'a'];
        yield ['12345678.', '.'];
    }

    public function testMissingVatCode()
    {
        $constraint = new TaxNumber([
            'messageVatCodeMissing' => 'myMessage',
            'vatCodeRequired' => true,
        ]);

        $this->validator->validate('12345678', $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '12345678')
            ->setCode(TaxNumber::MISSING_VAT_CODE_ERROR)
            ->assertRaised();
    }

    public function testNotAllowedVatCode()
    {
        $constraint = new TaxNumber([
            'messageVatCodeNotAllowed' => 'myMessage',
            'vatCodeRequired' => true,
            'allowedVatCodes' => [1, 2]
        ]);

        $this->validator->validate('123456785', $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '123456785')
            ->setParameter('{{ vat_code }}', '5')
            ->setParameter('{{ allowed_vat_codes }}', '1, 2')
            ->setCode(TaxNumber::NOT_ALLOWED_VAT_CODE_ERROR)
            ->assertRaised();
    }
    
    /**
     * @dataProvider getInvalidCountryCodes
     */
    public function testInvalidCountryCodes(string $taxNumber, string $expectedCountryCode)
    {
        $constraint = new TaxNumber([
            'messageCountryCodeInvalid' => 'myMessage',
        ]);

        $this->validator->validate($taxNumber, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', $taxNumber)
            ->setParameter('{{ country_code }}', $expectedCountryCode)
            ->setCode(TaxNumber::INVALID_COUNTRY_CODE_FORMAT_ERROR)
            ->assertRaised();
    }

    public function getInvalidCountryCodes(): \Generator
    {
        yield ['12345678100', '00'];
        yield ['123456781bb', 'bb'];
        yield ['123456781..', '..'];
    }

    public function testMissingCountryCode()
    {
        $constraint = new TaxNumber([
            'messageCountryCodeMissing' => 'myMessage',
            'countryCodeRequired' => true,
        ]);

        $this->validator->validate('123456781', $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '123456781')
            ->setCode(TaxNumber::MISSING_COUNTRY_CODE_ERROR)
            ->assertRaised();
    }

    public function testNotAllowedCountryCode()
    {
        $constraint = new TaxNumber([
            'messageCountryCodeNotAllowed' => 'myMessage',
            'vatCodeRequired' => true,
            'countryCodeRequired' => true,
            'allowedVatCodes' => [1, 2],
            'allowedCountryCodes' => [13, 33],
        ]);

        $this->validator->validate('12345678141', $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '12345678141')
            ->setParameter('{{ country_code }}', '41')
            ->setParameter('{{ allowed_country_codes }}', '13, 33')
            ->setCode(TaxNumber::NOT_ALLOWED_COUNTRY_CODE_ERROR)
            ->assertRaised();
    }

    public function testNotAllowedCountryAndVatCode()
    {
        $constraint = new TaxNumber([
            'messageCountryCodeNotAllowed' => 'myMessage',
            'messageVatCodeNotAllowed' => 'myMessage2',
            'vatCodeRequired' => true,
            'countryCodeRequired' => true,
            'allowedVatCodes' => [1, 2],
            'allowedCountryCodes' => [13, 33],
        ]);

        $this->validator->validate('12345678541', $constraint);

        $this
            ->buildViolation('myMessage2')
            ->setParameter('{{ value }}', '12345678541')
            ->setParameter('{{ vat_code }}', '5')
            ->setParameter('{{ allowed_vat_codes }}', '1, 2')
            ->setCode(TaxNumber::NOT_ALLOWED_VAT_CODE_ERROR)

            ->buildNextViolation('myMessage')
            ->setParameter('{{ value }}', '12345678541')
            ->setParameter('{{ country_code }}', '41')
            ->setParameter('{{ allowed_country_codes }}', '13, 33')
            ->setCode(TaxNumber::NOT_ALLOWED_COUNTRY_CODE_ERROR)

            ->assertRaised();
    }
}