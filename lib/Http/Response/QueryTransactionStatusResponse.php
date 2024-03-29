<?php

namespace NAV\OnlineInvoice\Http\Response;

use NAV\OnlineInvoice\Http\Response;

class QueryTransactionStatusResponse extends Response
{
    private $originalRequestVersion;
    
    public function setOriginalRequestVersion(string $originalRequestVersion): self
    {
        $this->originalRequestVersion = $originalRequestVersion;
        
        return $this;
    }
    
    public function getOriginalRequestVersion(): ?string
    {
        return $this->originalRequestVersion;
    }
    
    private $processingResults = [];
    
    public function addProcessingResult(array $processingResult): self
    {
        $this->processingResults[] = $processingResult;
        
        return $this;
    }
    
    public function getProcessingResults(): array
    {
        return $this->processingResults;
    }
    
    
}