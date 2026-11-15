<?php

namespace NAV\OnlineInvoice\Tests\Validator;

use NAV\OnlineInvoice\Validator\XSDValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(XSDValidator::class)]
class XSDValidatorTest extends TestCase
{
    private const SCHEMA = __DIR__.'/../Fixtures/xsd_validator_test.xsd';
    private const VALID = '<?xml version="1.0"?><root><a>1</a></root>';
    private const INVALID = '<?xml version="1.0"?><root><b>1</b></root>';

    public function testValidXmlHasNoErrors(): void
    {
        $validator = new XSDValidator(self::SCHEMA);
        $validator->validate(self::VALID);

        self::assertSame([], $validator->getErrors());
    }

    public function testInvalidXmlHasErrors(): void
    {
        $validator = new XSDValidator(self::SCHEMA);
        $validator->validate(self::INVALID);

        self::assertNotEmpty($validator->getErrors());
    }

    /** A valid document after an invalid one must not report the previous errors. */
    public function testErrorsAreResetAfterAFailedValidation(): void
    {
        $validator = new XSDValidator(self::SCHEMA);
        $validator->validate(self::INVALID);
        self::assertNotEmpty($validator->getErrors());

        $validator->validate(self::VALID);

        self::assertSame([], $validator->getErrors());
    }

    /** A second failing document must report only its own errors, not the accumulated libxml buffer. */
    public function testErrorsDoNotAccumulateAcrossValidations(): void
    {
        $validator = new XSDValidator(self::SCHEMA);
        $validator->validate(self::INVALID);
        $first = count($validator->getErrors());

        $validator->validate(self::INVALID);

        self::assertCount($first, $validator->getErrors());
    }

    public function testLibxmlStateIsRestored(): void
    {
        $previous = libxml_use_internal_errors(false);

        try {
            $validator = new XSDValidator(self::SCHEMA);
            $validator->validate(self::INVALID);

            self::assertFalse(libxml_use_internal_errors());
            self::assertSame([], libxml_get_errors());
        } finally {
            libxml_use_internal_errors($previous);
        }
    }
}
