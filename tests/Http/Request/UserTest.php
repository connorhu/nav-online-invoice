<?php

namespace NAV\Test\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\Request\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserTest extends TestCase
{
    private $validator;
    
    public function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->addXmlMapping('lib/Resources/validator/validation.xml')
            ->getValidator();
    }

    public function testTaxNumberValidation()
    {
        $user = new User();
        
        // blank
        $errors = $this->validator->validateProperty($user, 'taxNumber', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(NotBlank::IS_BLANK_ERROR, $errors[0]->getCode());

        // length < 8
        $user->setTaxNumber(str_repeat('1', 7));
        $errors = $this->validator->validateProperty($user, 'taxNumber', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(2, count($errors));
        $this->assertEquals(Length::TOO_SHORT_ERROR, $errors[0]->getCode());
        $this->assertEquals(Regex::REGEX_FAILED_ERROR, $errors[1]->getCode());

        // length > 8
        $user->setTaxNumber(str_repeat('1', 10));
        $errors = $this->validator->validateProperty($user, 'taxNumber', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(0, count($errors));

        // notmatch
        $user->setTaxNumber(str_repeat('a', 8));
        $errors = $this->validator->validateProperty($user, 'taxNumber', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(Regex::REGEX_FAILED_ERROR, $errors[0]->getCode());

        // valid
        $user->setTaxNumber('69061864-1-33');
        $errors = $this->validator->validateProperty($user, 'taxNumber', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(0, count($errors));
        $this->assertEquals($user->getTaxNumber(), '69061864');
    }
    
    public function testNotBlankFieldsValidation()
    {
        $user = new User();
        
        // blank
        $errors = $this->validator->validateProperty($user, 'signKey', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(NotBlank::IS_BLANK_ERROR, $errors[0]->getCode());
        
        // blank
        $errors = $this->validator->validateProperty($user, 'password', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(NotBlank::IS_BLANK_ERROR, $errors[0]->getCode());
        
        // blank
        $errors = $this->validator->validateProperty($user, 'login', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(NotBlank::IS_BLANK_ERROR, $errors[0]->getCode());
        
        // valid
        $user->setSignKey('abc');
        $errors = $this->validator->validateProperty($user, 'signKey', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(0, count($errors));
        $this->assertEquals($user->getSignKey(), 'abc');
        
        // valid
        $user->setPassword('abc');
        $errors = $this->validator->validateProperty($user, 'password', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(0, count($errors));
        $this->assertEquals($user->getPassword(), 'abc');
        
        // valid
        $user->setLogin('abc');
        $errors = $this->validator->validateProperty($user, 'login', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(0, count($errors));
        $this->assertEquals($user->getLogin(), 'abc');
    }
}
