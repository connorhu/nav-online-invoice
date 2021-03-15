<?php

namespace NAV\Test\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\Request\Software;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class SoftwareTest extends TestCase
{
    private $validator;
    
    public function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->addXmlMapping('lib/Resources/validator/validation.xml')
            ->getValidator();
    }
    
    public function testSoftwareIdValidation()
    {
        $software = new Software();
        
        // blank
        $errors = $this->validator->validateProperty($software, 'id', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(NotBlank::IS_BLANK_ERROR, $errors[0]->getCode());
        
        // not match
        $software->setId('invalid');
        $errors = $this->validator->validateProperty($software, 'id', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(Regex::REGEX_FAILED_ERROR, $errors[0]->getCode());

        // valid
        $software->setId('HU69061864-1234567');
        $errors = $this->validator->validateProperty($software, 'id', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(0, count($errors));
        $this->assertEquals($software->getId(), 'HU69061864-1234567');
    }

    public function testSoftwareNameValidation()
    {
        $software = new Software();

        // <= v1.1 blank
        $errors = $this->validator->validateProperty($software, 'name', ['v1.0', 'v1.1']);
        $this->assertEquals(0, count($errors));

        // > v1.1 blank
        $errors = $this->validator->validateProperty($software, 'name', ['v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(NotBlank::IS_BLANK_ERROR, $errors[0]->getCode());

        // length > 50
        $software->setName(str_repeat('1', 51));
        $errors = $this->validator->validateProperty($software, 'name', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(Length::TOO_LONG_ERROR, $errors[0]->getCode());

        // not match
        $software->setName("\n");
        $errors = $this->validator->validateProperty($software, 'name', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(Regex::REGEX_FAILED_ERROR, $errors[0]->getCode());

        // valid
        $software->setName('Nav api test @ php');
        $errors = $this->validator->validateProperty($software, 'name', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(0, count($errors));
        $this->assertEquals($software->getName(), 'Nav api test @ php');
    }
    
    public function testSoftwareOperationValidation()
    {
        $software = new Software();

        // <= v1.1 blank
        $errors = $this->validator->validateProperty($software, 'operation', ['v1.0', 'v1.1']);
        $this->assertEquals(0, count($errors));

        // > v1.1 blank
        $errors = $this->validator->validateProperty($software, 'operation', ['v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(NotBlank::IS_BLANK_ERROR, $errors[0]->getCode());
    
        // value not in list
        $software->setOperation('not in list');
        $errors = $this->validator->validateProperty($software, 'operation', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(Choice::NO_SUCH_CHOICE_ERROR, $errors[0]->getCode());

        // valid
        $software->setOperation(Software::OPERATION_ONLINE_SERVICE);
        $errors = $this->validator->validateProperty($software, 'operation', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(0, count($errors));
        $this->assertEquals($software->getOperation(), 'ONLINE_SERVICE');
    }
    
    public function testSoftwareMainVersionValidation()
    {
        $software = new Software();

        // <= v1.1 blank
        $errors = $this->validator->validateProperty($software, 'mainVersion', ['v1.0', 'v1.1']);
        $this->assertEquals(0, count($errors));

        // > v1.1 blank
        $errors = $this->validator->validateProperty($software, 'mainVersion', ['v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(NotBlank::IS_BLANK_ERROR, $errors[0]->getCode());

        // length > 15
        $software->setMainVersion(str_repeat('1', 16));
        $errors = $this->validator->validateProperty($software, 'mainVersion', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(Length::TOO_LONG_ERROR, $errors[0]->getCode());

        // not match
        $software->setMainVersion("\n");
        $errors = $this->validator->validateProperty($software, 'mainVersion', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(Regex::REGEX_FAILED_ERROR, $errors[0]->getCode());

        // valid
        $software->setMainVersion('v1.0');
        $errors = $this->validator->validateProperty($software, 'mainVersion', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(0, count($errors));
        $this->assertEquals($software->getMainVersion(), 'v1.0');
    }
    
    public function testSoftwareDevNameValidation()
    {
        $software = new Software();

        // <= v1.1 blank
        $errors = $this->validator->validateProperty($software, 'devName', ['v1.0', 'v1.1']);
        $this->assertEquals(0, count($errors));

        // > v1.1 blank
        $errors = $this->validator->validateProperty($software, 'devName', ['v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(NotBlank::IS_BLANK_ERROR, $errors[0]->getCode());

        // length > 15
        $software->setDevName(str_repeat('1', 513));
        $errors = $this->validator->validateProperty($software, 'devName', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(Length::TOO_LONG_ERROR, $errors[0]->getCode());

        // not match
        $software->setDevName("\n");
        $errors = $this->validator->validateProperty($software, 'devName', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(Regex::REGEX_FAILED_ERROR, $errors[0]->getCode());

        // valid
        $software->setDevName('v1.0');
        $errors = $this->validator->validateProperty($software, 'devName', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(0, count($errors));
        $this->assertEquals($software->getDevName(), 'v1.0');
    }
    
    public function testSoftwareDevContactValidation()
    {
        $software = new Software();

        // <= v1.1 blank
        $errors = $this->validator->validateProperty($software, 'devContact', ['v1.0', 'v1.1']);
        $this->assertEquals(0, count($errors));

        // > v1.1 blank
        $errors = $this->validator->validateProperty($software, 'devContact', ['v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(NotBlank::IS_BLANK_ERROR, $errors[0]->getCode());

        // length > 15
        $software->setDevContact(str_repeat('1', 513));
        $errors = $this->validator->validateProperty($software, 'devContact', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(Length::TOO_LONG_ERROR, $errors[0]->getCode());

        // not match
        $software->setDevContact("\n");
        $errors = $this->validator->validateProperty($software, 'devContact', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(Regex::REGEX_FAILED_ERROR, $errors[0]->getCode());

        // valid
        $software->setDevContact('connor at connor dot hu');
        $errors = $this->validator->validateProperty($software, 'devContact', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(0, count($errors));
        $this->assertEquals($software->getDevContact(), 'connor at connor dot hu');
    }
    
    public function testSoftwareDevCountryCodeValidation()
    {
        $software = new Software();

        // blank
        $errors = $this->validator->validateProperty($software, 'devCountryCode', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(0, count($errors));

        // // length > 2 and not in list
        $software->setDevCountryCode(str_repeat('A', 3));
        $errors = $this->validator->validateProperty($software, 'devCountryCode', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(2, count($errors));
        $this->assertEquals(Length::TOO_LONG_ERROR, $errors[0]->getCode());
        $this->assertEquals(Country::NO_SUCH_COUNTRY_ERROR, $errors[1]->getCode());

        // valid
        $software->setDevCountryCode('HU');
        $errors = $this->validator->validateProperty($software, 'devCountryCode', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(0, count($errors));
        $this->assertEquals($software->getDevCountryCode(), 'HU');
    }
    
    public function testSoftwareDevTaxNumberValidation()
    {
        $software = new Software();

        // blank
        $errors = $this->validator->validateProperty($software, 'devTaxNumber', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(0, count($errors));

        // length > 50
        $software->setDevTaxNumber(str_repeat('1', 51));
        $errors = $this->validator->validateProperty($software, 'devTaxNumber', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(Length::TOO_LONG_ERROR, $errors[0]->getCode());

        // not match
        $software->setDevTaxNumber("\n");
        $errors = $this->validator->validateProperty($software, 'devTaxNumber', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(Regex::REGEX_FAILED_ERROR, $errors[0]->getCode());

        // valid
        $software->setDevTaxNumber('69061864-1-33');
        $errors = $this->validator->validateProperty($software, 'devTaxNumber', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(0, count($errors));
        $this->assertEquals($software->getDevTaxNumber(), '69061864-1-33');
    }
    
}