<?php

namespace NAV\OnlineInvoice\Http\Response;

use NAV\OnlineInvoice\Http\Response;

class GeneralExceptionResponse extends Response implements ErrorResponse
{
    private $functionCode;
    private $errorCode;
    private $message;

    /**
     * @var list<array{code: ?string, text: ?string}>
     */
    private array $notifications = [];
    
    public function setFunctionCode(?string $functionCode): self
    {
        $this->functionCode = $functionCode;
        
        return $this;
    }
    
    public function getFunctionCode(): ?string
    {
        return $this->functionCode;
    }
    
    public function setErrorCode(?string $errorCode): self
    {
        $this->errorCode = $errorCode;
        
        return $this;
    }
    
    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }
    
    public function setMessage(?string $message): self
    {
        $this->message = $message;
        
        return $this;
    }
    
    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function addNotification(?string $code, ?string $text): self
    {
        $this->notifications[] = ['code' => $code, 'text' => $text];

        return $this;
    }

    /**
     * The NAV notifications of the response, e.g. the individual SCHEMA_VIOLATION details.
     *
     * @return list<array{code: ?string, text: ?string}>
     */
    public function getNotifications(): array
    {
        return $this->notifications;
    }
}
