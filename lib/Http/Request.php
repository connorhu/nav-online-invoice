<?php

namespace NAV\OnlineInvoice\Http;

abstract class Request
{
    const REQUEST_VERSION_V10 = '1.0';
    const REQUEST_VERSION_V11 = '1.1';
    const REQUEST_VERSION_V20 = '2.0';
    const REQUEST_VERSION_V30 = '3.0';

    abstract public function getEndpointPath(): string;

    protected $requestVersion = self::REQUEST_VERSION_V11;
    
    /**
     * setter for requestVersion
     *
     * @param mixed 
     * @return self
     */
    public function setRequestVersion($value)
    {
        $this->requestVersion = $value;
        return $this;
    }
    
    /**
     * getter for requestVersion
     * 
     * @return mixed return value for 
     */
    public function getRequestVersion()
    {
        return $this->requestVersion;
    }
    
    // TODO [+a-zA-Z0-9_]{1,30}
    private $requestId;
    
    /**
     * setter for requestId
     *
     * @param mixed 
     * @return self
     */
    public function setRequestId(string $value): self
    {
        $this->requestId = $value;
        
        return $this;
    }
    
    /**
     * getter for requestId
     * 
     * @return mixed return value for 
     */
    public function getRequestId(): string
    {
        return $this->requestId;
    }
}