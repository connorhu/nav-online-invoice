<?php

namespace NAV\OnlineInvoice\Exceptions;

use Symfony\Component\Validator\ConstraintViolationList;

class ConstraintViolationException extends \Exception
{
    private ConstraintViolationList $violationList;

    public function __construct($message, ConstraintViolationList $list)
    {
        parent::__construct($message);
        $this->violationList = $list;
    }
    
    public function getViolationList(): ConstraintViolationList
    {
        return $this->violationList;
    }
}
