<?php

namespace NAV\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\Request\HeaderAwareRequest;

class Header
{
    const HEADER_VERSION_V10 = '1.0';
    
    protected $request;
    
    /**
     * setter for request
     *
     * @param mixed 
     * @return self
     */
    public function setRequest(HeaderAwareRequest $value): self
    {
        if ($this->request !== $value) {
            $this->request = $value;
        }
        
        return $this;
    }
    
    /**
     * getter for request
     * 
     * @return mixed return value for 
     */
    public function getRequest(): HeaderAwareRequest
    {
        return $this->request;
    }
    
    protected $headerVersion = self::HEADER_VERSION_V10;
    
    /**
     * setter for headerVersion
     *
     * @param mixed 
     * @return self
     */
    public function setHeaderVersion(string $value): self
    {
        $this->headerVersion = $value;
        return $this;
    }
    
    /**
     * getter for headerVersion
     * 
     * @return mixed return value for 
     */
    public function getHeaderVersion(): string
    {
        return $this->headerVersion;
    }
    
    protected $timestamp;
    
    /**
     * setter for timestamp
     *
     * @param mixed 
     * @return self
     */
    public function setTimestamp(\DateTime $value): self
    {
        if ($value->getTimezone()->getName() !== 'UTC') {
            $value->setTimezone(new \DateTimeZone('UTC'));
        }

        $this->timestamp = $value;
        return $this;
    }
    
    /**
     * getter for timestamp
     * 
     * @return mixed return value for 
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }
}