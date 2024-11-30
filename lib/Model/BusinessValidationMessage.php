<?php

namespace NAV\OnlineInvoice\Model;

use NAV\OnlineInvoice\Model\TechnicalValidationMessage;

class BusinessValidationMessage extends TechnicalValidationMessage
{
    public ?string $pointerTag = null;

    public ?string $pointerValue = null;

    public ?string $pointerLine = null;

    public ?string $pointerOriginalInvoiceNumber = null;
}
