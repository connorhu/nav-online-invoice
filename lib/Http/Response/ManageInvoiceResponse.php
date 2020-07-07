<?php

namespace NAV\OnlineInvoice\Http\Response;

use NAV\OnlineInvoice\Token\RequestToken;
use NAV\OnlineInvoice\Http\Response;

class ManageInvoiceResponse extends Response
{
    private $transactionId;
    
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }
    
    public function setTransactionId(string $transactionId): self
    {
        $this->transactionId = $transactionId;
        return $this;
    }
}
