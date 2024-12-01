<?php

namespace NAV\OnlineInvoice\Tests\Http\Request;

use NAV\OnlineInvoice\Http\Request\QueryTaxpayerRequest;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[DataProvider('taxNumberValidationDataProvider')]
    public function testTaxNumberValidation(callable $setup, int $numberOfError, callable $asserts)
    {
        $request = new QueryTaxpayerRequest();

        $setup($request);
        $errors = $this->validator->validateProperty($request, 'taxNumber', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertCount($numberOfError, $errors);
        $asserts($errors, $request);
    }

    public static function taxNumberValidationDataProvider(): \Generator
    {
        yield [function (QueryTaxpayerRequest $request) {

        }, 1, function ($errors, $request) {
            self::assertEquals(NotBlank::IS_BLANK_ERROR, $errors[0]->getCode());
        }];

        yield [function (QueryTaxpayerRequest $request) {
            $request->setTaxNumber(str_repeat('1', 7));
        }, 2, function ($errors, $request) {
            self::assertEquals(Length::NOT_EQUAL_LENGTH_ERROR, $errors[0]->getCode());
            self::assertEquals(Regex::REGEX_FAILED_ERROR, $errors[1]->getCode());
        }];

        yield [function (QueryTaxpayerRequest $request) {
            $request->setTaxNumber(str_repeat('1', 10));
        }, 0, function ($errors, $request) {
        }];

        yield [function (QueryTaxpayerRequest $request) {
            $request->setTaxNumber(str_repeat('a', 8));
        }, 1, function ($errors, $request) {
            self::assertEquals(Regex::REGEX_FAILED_ERROR, $errors[0]->getCode());
        }];

        yield [function (QueryTaxpayerRequest $request) {
            $request->setTaxNumber('69061864-1-33');
        }, 0, function ($errors, $request) {
            self::assertEquals('69061864', $request->getTaxNumber());
        }];
    }
}
