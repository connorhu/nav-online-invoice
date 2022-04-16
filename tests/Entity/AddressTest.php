<?php

namespace NAV\Tests\OnlineInvoice\Entity;

use NAV\OnlineInvoice\Entity\Address;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddressTest extends TestCase
{
    private ValidatorInterface $validator;

    public function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->addXmlMapping('lib/Resources/validator/validation.xml')
            ->getValidator();
    }

    public function testBlankFields()
    {
        $address = new Address();

        $errors = $this->validator->validateProperty($address, 'countryCode', 'v3.0');
        $this->assertCount(1, $errors);

        $errors = $this->validator->validateProperty($address, 'postalCode', 'v3.0');
        $this->assertCount(1, $errors);

        $errors = $this->validator->validateProperty($address, 'city', 'v3.0');
        $this->assertCount(1, $errors);

        /**
         * @var array<int, ConstraintViolation> $errors
         */
        $errors = $this->validator->validate($address, null, 'v3.0');
        $this->assertCount(4, $errors);

        $this->assertSame(Address::STREET_IS_BLANK_ERROR, $errors[0]->getCode());

        $this->assertSame(NotBlank::IS_BLANK_ERROR, $errors[1]->getCode());
        $this->assertSame('countryCode', $errors[1]->getPropertyPath());
        $this->assertSame(NotBlank::IS_BLANK_ERROR, $errors[2]->getCode());
        $this->assertSame('postalCode', $errors[2]->getPropertyPath());
        $this->assertSame(NotBlank::IS_BLANK_ERROR, $errors[3]->getCode());
        $this->assertSame('city', $errors[3]->getPropertyPath());
    }

    public function testBlankStreet()
    {
        $address = new Address();
        $address->setCountryCode('HU')
            ->setPostalCode('2100')
            ->setCity('city');

        $errors = $this->validator->validate($address, null, 'v3.0');
        $this->assertCount(1, $errors);
        $this->assertSame(Address::STREET_IS_BLANK_ERROR, $errors[0]->getCode());

        $address->setStreetName('nonblank');
        $errors = $this->validator->validate($address, null, 'v3.0');
        $this->assertCount(1, $errors);
        $this->assertSame(Address::STREET_IS_BLANK_ERROR, $errors[0]->getCode());

        $address->setStreetName('');
        $address->setPublicPlaceCategory('utca');
        $errors = $this->validator->validate($address, null, 'v3.0');
        $this->assertCount(1, $errors);
        $this->assertSame(Address::STREET_IS_BLANK_ERROR, $errors[0]->getCode());
    }

    public function testValidStreet()
    {
        $address = new Address();
        $address->setCountryCode('HU')
            ->setPostalCode('2100')
            ->setCity('city')
            ->setAdditionalAddressDetail('street');

        $errors = $this->validator->validate($address, null, 'v3.0');
        $this->assertCount(0, $errors);

        $address = new Address();
        $address->setCountryCode('HU')
            ->setPostalCode('2100')
            ->setCity('city')
            ->setStreetName('street')
            ->setPublicPlaceCategory('utca');

        $errors = $this->validator->validate($address, null, 'v3.0');
        $this->assertCount(0, $errors);
    }
}

