<?php

namespace NAV\OnlineInvoice\Model;

use NAV\OnlineInvoice\Model\Enums\ValidationResultCodeEnum;

class TechnicalValidationMessage
{
    public ValidationResultCodeEnum $resultCode;

    public ?string $errorCode = null;

    public ?string $message = null;
}
