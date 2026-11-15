<?php

namespace NAV\OnlineInvoice\Tests\Http\Request;

use NAV\OnlineInvoice\Http\Request\Header;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class HeaderTest extends TestCase
{
    private $validator;
    
    public function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->addXmlMapping('lib/Resources/validator/validation.xml')
            ->getValidator();
    }
    
    public function testTimestampValidation()
    {
        $header = new Header();
        
        // blank
        $errors = $this->validator->validateProperty($header, 'timestamp', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(1, count($errors));
        $this->assertEquals(NotBlank::IS_BLANK_ERROR, $errors[0]->getCode());

        // valid
        $header->setTimestamp($date = new \DateTimeImmutable());
        $errors = $this->validator->validateProperty($header, 'timestamp', ['v1.0', 'v1.1', 'v2.0', 'v3.0']);
        $this->assertEquals(0, count($errors));
        $this->assertEquals($header->getTimestamp(), $date);
        $this->assertEquals($header->getTimestamp()->getTimezone()->getName(), 'UTC');
    }

    public function testTimestampIsConvertedToUtc(): void
    {
        $header = new Header();
        $header->setTimestamp(new \DateTimeImmutable('2020-07-01 12:00:00', new \DateTimeZone('Europe/Budapest')));

        $this->assertSame('UTC', $header->getTimestamp()->getTimezone()->getName());
        $this->assertSame('2020-07-01 10:00:00', $header->getTimestamp()->format('Y-m-d H:i:s'));
    }
}
