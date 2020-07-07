<?php

namespace NAV\OnlineInvoice\Http\Response;

use NAV\OnlineInvoice\Http\Response;

class TokenExchangeResponse extends Response
{
    private $validFrom;
    
    private $validTo;
    
    private $exhangeToken;
    
    public function getValidFrom(): \DateTime
    {
        return $this->validFrom;
    }

    public function setValidFrom(\DateTime $validFrom): self
    {
        $this->validFrom = $validFrom;
        
        return $this;
    }
    
    public function getValidTo(): \DateTime
    {
        return $this->validTo;
    }

    public function setValidTo(\DateTime $validTo): self
    {
        $this->validTo = $validTo;
        
        return $this;
    }
    
    public function getExchangeToken(): string
    {
        return $this->exchangeToken;
    }

    public function setExchangeToken(string $exchangeToken): self
    {
        $this->exchangeToken = $exchangeToken;
        
        return $this;
    }
}