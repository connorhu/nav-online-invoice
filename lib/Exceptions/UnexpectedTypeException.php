<?php

namespace NAV\OnlineInvoice\Exceptions;

class UnexpectedTypeException extends InvalidArgumentException
{
    public function __construct(mixed $value, string $expectedType)
    {
        parent::__construct(\sprintf('Expected argument of type "%s", but "%s" given', $expectedType, get_debug_type($value)));
    }
}
