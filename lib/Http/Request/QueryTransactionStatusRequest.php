<?php

namespace NAV\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\Request;

class QueryTransactionStatusRequest extends Request
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getRootNodeName()
    {
        return 'QueryTransactionStatusRequest';
    }

    public function getEndpointPath()
    {
        return '/queryTransactionStatus';
    }
    
    protected $transactionId;
    
    /**
     * setter for transactionId
     *
     * @param mixed 
     * @return self
     */
    public function setTransactionId(string $value): self
    {
        $this->transactionId = $value;
        return $this;
    }
    
    /**
     * getter for transactionId
     * 
     * @return mixed return value for 
     */
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }
    
    protected $returnOriginalRequest = false;
    
    /**
     * setter for returnOriginalRequest
     *
     * @param mixed 
     * @return self
     */
    public function setReturnOriginalRequest(bool $value)
    {
        $this->returnOriginalRequest = $value;
        return $this;
    }
    
    /**
     * getter for returnOriginalRequest
     * 
     * @return mixed return value for 
     */
    public function getReturnOriginalRequest(): bool
    {
        return $this->returnOriginalRequest;
    }
}