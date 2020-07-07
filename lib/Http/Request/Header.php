<?php

namespace NAV\OnlineInvoice\Http\Request;

use DateTimeZone;
use NAV\OnlineInvoice\XML\XMLWriter;

class Header
{
    const HEADER_VERSION_V10 = '1.0';

    const REQUEST_VERSION_V10 = '1.0';
    const REQUEST_VERSION_V11 = '1.1';
    const REQUEST_VERSION_V20 = '2.0';
    
    protected $timestamp;
    
    /**
     * setter for timestamp
     *
     * @param mixed 
     * @return self
     */
    public function setTimestamp(\DateTime $value)
    {
        if ($value->getTimezone()->getName() !== 'UTC') {
            $value->setTimezone(new DateTimeZone('UTC'));
        }

        $this->timestamp = $value;
        return $this;
    }
    
    /**
     * getter for timestamp
     * 
     * @return mixed return value for 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
    
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
    
    protected $headerVersion = self::HEADER_VERSION_V10;
    
    /**
     * setter for headerVersion
     *
     * @param mixed 
     * @return self
     */
    public function setHeaderVersion($value)
    {
        $this->headerVersion = $value;
        return $this;
    }
    
    /**
     * getter for headerVersion
     * 
     * @return mixed return value for 
     */
    public function getHeaderVersion()
    {
        return $this->headerVersion;
    }
}