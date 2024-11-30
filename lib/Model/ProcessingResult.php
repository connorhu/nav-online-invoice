<?php

namespace NAV\OnlineInvoice\Model;

use NAV\OnlineInvoice\Http\Enums\RequestVersionEnum;
use NAV\OnlineInvoice\Model\Enums\InvoiceStatusEnum;

class ProcessingResult
{
    public int $index;

    public ?int $batchIndex = null;

    public ?InvoiceStatusEnum $status = null;

    public bool $compressedContent;

    /**
     * @var array<int, TechnicalValidationMessage>
     */
    public array $technicalValidationMessages = [];

    /**
     * @var array<int, BusinessValidationMessage>
     */
    public array $businessValidationMessages = [];
}
