<?php

namespace NAV\OnlineInvoice\Http\Response;

use NAV\OnlineInvoice\Http\Enums\RequestVersionEnum;
use NAV\OnlineInvoice\Http\Response;
use NAV\OnlineInvoice\Model\ProcessingResult;

class QueryTransactionStatusResponse extends Response
{
    private RequestVersionEnum $originalRequestVersion;

    /**
     * @var array<int, ProcessingResult>
     */
    private array $processingResults = [];

    public function getOriginalRequestVersion(): RequestVersionEnum
    {
        return $this->originalRequestVersion;
    }

    public function setOriginalRequestVersion(RequestVersionEnum $originalRequestVersion): self
    {
        $this->originalRequestVersion = $originalRequestVersion;
        
        return $this;
    }

    /**
     * @return array<int, ProcessingResult>
     */
    public function getProcessingResults(): array
    {
        return $this->processingResults;
    }

    public function addProcessingResult(ProcessingResult $processingResult): self
    {
        $this->processingResults[] = $processingResult;
        
        return $this;
    }

}
