<?php

namespace NAV\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\RequestServiceKindEnum;

class QueryTransactionStatusRequest extends Request implements HeaderAwareRequest, UserAwareRequest, SoftwareAwareRequest
{
    use UserAwareTrait;
    use HeaderAwareTrait;
    use SoftwareAwareTrait;

    const ROOT_NODE_NAME = 'QueryTransactionStatusRequest';

    public function getEndpointPath(): string
    {
        return '/queryTransactionStatus';
    }

    public function getServiceKind(): RequestServiceKindEnum
    {
        return RequestServiceKindEnum::InvoiceService;
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
    
    protected bool $returnOriginalRequest = false;
    
    /**
     * setter for returnOriginalRequest
     *
     * @param mixed 
     * @return self
     */
    public function setReturnOriginalRequest(bool $value): QueryTransactionStatusRequest
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
