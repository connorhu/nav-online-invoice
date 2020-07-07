<?php

namespace NAV\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\Request;

class QueryTaxpayerRequest extends Request
{
    public function __construct()
    {
        parent::__construct();
    }
    
    protected $taxNumber;
    
    /**
     * setter for taxNumber
     *
     * @param mixed 
     * @return self
     */
    public function setTaxNumber($value)
    {
        $this->taxNumber = substr($value, 0, 8);
        return $this;
    }
    
    /**
     * getter for taxNumber
     * 
     * @return mixed return value for 
     */
    public function getTaxNumber()
    {
        return $this->taxNumber;
    }
    
    public function getRootNodeName(): string
    {
        return 'QueryTaxpayerRequest';
    }
    
    public function getEndpointPath(): string
    {
        return '/queryTaxpayer';
    }
}