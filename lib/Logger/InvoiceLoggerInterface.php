<?php

namespace NAV\OnlineInvoice\Logger;

use NAV\OnlineInvoice\Model\Interfaces\InvoiceInterface;

interface InvoiceLoggerInterface
{
    public function logInvoice(InvoiceInterface $invoice, string $xmlContent): void;
}
