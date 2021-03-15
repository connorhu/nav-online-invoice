<?php

namespace NAV\Test\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\Request\QueryTaxpayerRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class QueryTaxpayerRequestTest extends TestCase
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
        $request = new QueryTaxpayerRequest();
        
        // blank
        $errors = $this->validator->validateProperty($request, 'taxNumber', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(NotBlank::IS_BLANK_ERROR, $errors[0]->getCode());

        // length < 8
        $request->setTaxNumber(str_repeat('1', 7));
        $errors = $this->validator->validateProperty($request, 'taxNumber', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(2, count($errors));
        $this->assertEquals(Length::TOO_SHORT_ERROR, $errors[0]->getCode());
        $this->assertEquals(Regex::REGEX_FAILED_ERROR, $errors[1]->getCode());

        // length > 8
        $request->setTaxNumber(str_repeat('1', 10));
        $errors = $this->validator->validateProperty($request, 'taxNumber', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(0, count($errors));

        // notmatch
        $request->setTaxNumber(str_repeat('a', 8));
        $errors = $this->validator->validateProperty($request, 'taxNumber', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(Regex::REGEX_FAILED_ERROR, $errors[0]->getCode());

        // valid
        $request->setTaxNumber('69061864-1-33');
        $errors = $this->validator->validateProperty($request, 'taxNumber', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(0, count($errors));
        $this->assertEquals($request->getTaxNumber(), '69061864');
    }
}