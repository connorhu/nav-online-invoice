<?php

namespace NAV\OnlineInvoice\Http\Response;

use NAV\OnlineInvoice\Http\Response;

class QueryInvoiceCheckResponse extends Response
{
    private bool $invoiceCheckResult = false;

    /**
     * True if the queried invoice number exists exactly once as a valid invoice and the
     * querying taxpayer is its supplier (OUTBOUND) or customer (INBOUND).
     */
    public function getInvoiceCheckResult(): bool
    {
        return $this->invoiceCheckResult;
    }

    public function setInvoiceCheckResult(bool $invoiceCheckResult): self
    {
        $this->invoiceCheckResult = $invoiceCheckResult;

        return $this;
    }
}
