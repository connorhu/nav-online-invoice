<?php

namespace NAV\OnlineInvoice\Tests\Http\Request;

use NAV\OnlineInvoice\Http\Request\User;
use NAV\OnlineInvoice\Tests\Fixtures\UserAwareTraitImplementation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
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

    public function testValidTaxNumberValidation()
    {
        $user = new User();
        $user->setTaxNumber('69061864-1-33');
        $errors = $this->validator->validateProperty($user, 'taxNumber', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertCount(0, $errors);
        $this->assertEquals('69061864', $user->getTaxNumber());
    }

    #[DataProvider('invalidTaxNumberValidationDataProvider')]
    public function testInvalidTaxNumberValidation(User $user, int $numberOfErrors, array $errorAsserts)
    {
        $errors = $this->validator->validateProperty($user, 'taxNumber', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertCount($numberOfErrors, $errors);
        foreach ($errorAsserts as $errorAssert) {
            $this->assertEquals($errorAssert[1], $errors[$errorAssert[0]]->getCode());
        }
    }

    public static function invalidTaxNumberValidationDataProvider(): \Generator
    {
        yield [
            (new User()), 2, [
                [0, Length::NOT_EQUAL_LENGTH_ERROR],
                [1, NotBlank::IS_BLANK_ERROR],
            ],
        ];

        yield [
            (new User())->setTaxNumber(str_repeat('1', 7)), 2, [
                [0, Length::NOT_EQUAL_LENGTH_ERROR],
                [1, Regex::REGEX_FAILED_ERROR],
            ],
        ];

        yield [
            (new User())->setTaxNumber(str_repeat('1', 10)), 0, [
            ],
        ];

        yield [
            (new User())->setTaxNumber(str_repeat('a', 8)), 1, [
                [0, Regex::REGEX_FAILED_ERROR],
            ],
        ];

        yield [
            (new User())->setTaxNumber(str_repeat('a', 8)), 1, [
                [0, Regex::REGEX_FAILED_ERROR],
            ],
        ];
    }

    #[DataProvider('notBlankFieldsValidationDataProvider')]
    public function testNotBlankFieldsValidation($propertyName)
    {
        $user = new User();
        $errors = $this->validator->validateProperty($user, $propertyName, ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertCount(1, $errors);
        $this->assertEquals(NotBlank::IS_BLANK_ERROR, $errors[0]->getCode());
    }

    public static function notBlankFieldsValidationDataProvider(): \Generator
    {
        yield [
            'signKey',
        ];
        yield [
            'password',
        ];
        yield [
            'login',
        ];
    }

    #[DataProvider('validNotBlankFieldsValidationDataProvider')]
    public function testValidNotBlankFieldsValidation(string $methodName, string $value, string $propertyName)
    {
        $user = new User();
        $user->{'set'.$methodName}($value);
        $errors = $this->validator->validateProperty($user, $propertyName, ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertCount(0, $errors);
        $this->assertEquals($value, $user->{'get'.$methodName}());
    }

    public static function validNotBlankFieldsValidationDataProvider(): \Generator
    {
        yield [
            'SignKey', 'abc', 'signKey'
        ];

        yield [
            'Password', 'abc', 'password'
        ];

        yield [
            'Login', 'abc', 'login'
        ];
    }

    public function testSetRequest()
    {
        $user = new User();
        $request = new UserAwareTraitImplementation();

        $user->setRequest($request);
        $this->assertSame($user, $request->getUser());
        $this->assertSame($request, $user->getRequest());
    }
}
