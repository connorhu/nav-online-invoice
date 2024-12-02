<?php

namespace NAV\OnlineInvoice\Logger;

use NAV\OnlineInvoice\Model\Interfaces\InvoiceInterface;

class NullInvoiceLogger implements InvoiceLoggerInterface
{
    public function logInvoice(InvoiceInterface $invoice, string $xmlContent): void
    {
    }
}
