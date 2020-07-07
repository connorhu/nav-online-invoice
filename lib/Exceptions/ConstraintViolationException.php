<?php

namespace NAV\OnlineInvoice\Exceptions;

use Symfony\Component\Validator\ConstraintViolationList;

class ConstraintViolationException extends \Exception
{
    public function __construct($message, ConstraintViolationList $list)
    {
        $this->message = $message;
        $this->violationList = $list;
    }
    
    public function getViolationList(): ConstraintViolationList
    {
        return $this->violationList;
    }
}