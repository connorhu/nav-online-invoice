<?php

namespace NAV\OnlineInvoice\Http;

trait ExhangeTokenTrait
{
    protected $exchangeToken;
    
    public function setExchangeToken(string $token)
    {
        $this->exchangeToken = $token;
        return $this;
    }

    public function getExchangeToken(): string
    {
        return $this->exchangeToken;
    }
}