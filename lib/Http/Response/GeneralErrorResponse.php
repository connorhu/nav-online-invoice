<?php

namespace NAV\OnlineInvoice\Http\Response;

use NAV\OnlineInvoice\Http\Response;

class GeneralErrorResponse extends Response implements ErrorResponse
{
    private $functionCode;
    private $errorCode;
    private $message;
    private $technicalValidationMessages = [];
    
    public function setFunctionCode(string $functionCode): self
    {
        $this->functionCode = $functionCode;
        
        return $this;
    }
    
    public function getFunctionCode(): ?string
    {
        return $this->functionCode;
    }
    
    public function setErrorCode(string $errorCode): self
    {
        $this->errorCode = $errorCode;
        
        return $this;
    }
    
    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }
    
    public function setMessage(string $message): self
    {
        $this->message = $message;
        
        return $this;
    }
    
    public function getMessage(): ?string
    {
        return $this->message;
    }
    
    public function addTechnicalValidationMessage(array $technicalValidationMessage): self
    {
        $this->technicalValidationMessages[] = $technicalValidationMessage;
        
        return $this;
    }
    
    public function getTechnicalValidationMessages(): array
    {
        return $this->technicalValidationMessage;
    }
    
    
}