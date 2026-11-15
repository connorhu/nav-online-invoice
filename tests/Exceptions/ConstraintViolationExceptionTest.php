<?php

namespace NAV\OnlineInvoice\Tests\Exceptions;

use NAV\OnlineInvoice\Exceptions\ConstraintViolationException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class ConstraintViolationExceptionTest extends TestCase
{
    public function testMessageAndViolationList(): void
    {
        $list = new ConstraintViolationList([
            new ConstraintViolation('invalid', null, [], null, 'invoiceNumber', null),
        ]);

        $exception = new ConstraintViolationException('The request is invalid', $list);

        $this->assertSame('The request is invalid', $exception->getMessage());
        $this->assertSame($list, $exception->getViolationList());
        $this->assertFalse(
            (new \ReflectionObject($exception))->getProperty('violationList')->isPublic(),
            'violationList must be a declared (non-dynamic) property'
        );
    }
}
