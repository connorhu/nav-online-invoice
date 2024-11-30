<?php

namespace NAV\OnlineInvoice\Tests\Validator;

use NAV\OnlineInvoice\Validator\Constraints\TaxNumber;
use NAV\OnlineInvoice\Validator\Constraints\TaxNumberValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class TaxNumberValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ConstraintValidatorInterface
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
     * @dataProvider validTaxNumbersDataProvider
     */
    public function testValidTaxNumbers($taxNumber)
    {
        $this->validator->validate($taxNumber, new TaxNumber());

        $this->assertNoViolation();
    }

    public static function validTaxNumbersDataProvider()
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
     * @dataProvider shortTaxNumbersDataProvider
     */
    public function testShortTaxPayerId(string $taxNumber)
    {
        $constraint = new TaxNumber([
            'messageTaxpayerIdShort' => 'myMessage',
        ]);

        $this->validator->validate($taxNumber, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', $taxNumber)
            ->setCode(TaxNumber::INVALID_TAXPAYER_SHORT_ERROR)
            ->assertRaised();
    }

    public static function shortTaxNumbersDataProvider(): \Generator
    {
        yield ['1111'];
        yield ['abc214'];
    }

    /**
     * @dataProvider invalidTaxNumbersDataProvider
     */
    public function testInvalidTaxPayerId(string $taxNumber)
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

    public static function invalidTaxNumbersDataProvider(): \Generator
    {
        yield ['1234567s'];
        yield ['..123456'];
    }
    
    /**
     * @dataProvider invalidVatCodesDataProvider
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

    public static function invalidVatCodesDataProvider(): \Generator
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
     * @dataProvider invalidCountyCodesDataProvider
     */
    public function testInvalidCountyCodes(string $taxNumber, string $expectedCountyCode)
    {
        $constraint = new TaxNumber([
            'messageCountyCodeInvalid' => 'myMessage',
        ]);

        $this->validator->validate($taxNumber, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', $taxNumber)
            ->setParameter('{{ county_code }}', $expectedCountyCode)
            ->setCode(TaxNumber::INVALID_COUNTY_CODE_FORMAT_ERROR)
            ->assertRaised();
    }

    public static function invalidCountyCodesDataProvider(): \Generator
    {
        yield ['12345678100', '00'];
        yield ['123456781bb', 'bb'];
        yield ['123456781..', '..'];
    }

    public function testMissingCountyCode()
    {
        $constraint = new TaxNumber([
            'messageCountyCodeMissing' => 'myMessage',
            'countyCodeRequired' => true,
        ]);

        $this->validator->validate('123456781', $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '123456781')
            ->setCode(TaxNumber::MISSING_COUNTY_CODE_ERROR)
            ->assertRaised();
    }

    public function testNotAllowedCountyCode()
    {
        $constraint = new TaxNumber([
            'messageCountyCodeNotAllowed' => 'myMessage',
            'vatCodeRequired' => true,
            'countyCodeRequired' => true,
            'allowedVatCodes' => [1, 2],
            'allowedCountyCodes' => [13, 33],
        ]);

        $this->validator->validate('12345678141', $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '12345678141')
            ->setParameter('{{ county_code }}', '41')
            ->setParameter('{{ allowed_county_codes }}', '13, 33')
            ->setCode(TaxNumber::NOT_ALLOWED_COUNTY_CODE_ERROR)
            ->assertRaised();
    }

    public function testNotAllowedCountyAndVatCode()
    {
        $constraint = new TaxNumber([
            'messageCountyCodeNotAllowed' => 'myMessage',
            'messageVatCodeNotAllowed' => 'myMessage2',
            'vatCodeRequired' => true,
            'countyCodeRequired' => true,
            'allowedVatCodes' => [1, 2],
            'allowedCountyCodes' => [13, 33],
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
            ->setParameter('{{ county_code }}', '41')
            ->setParameter('{{ allowed_county_codes }}', '13, 33')
            ->setCode(TaxNumber::NOT_ALLOWED_COUNTY_CODE_ERROR)

            ->assertRaised();
    }
}