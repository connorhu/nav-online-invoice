<?php

namespace NAV\OnlineInvoice\Model;

use NAV\OnlineInvoice\Model\Interfaces\VatRateInterface;
use NAV\OnlineInvoice\Model\Interfaces\VatRateSummaryInterface;
use NAV\OnlineInvoice\Model\Traits\VatRateTrait;

class VatRateSummary implements VatRateInterface, VatRateSummaryInterface
{
    use VatRateTrait;
    
    /*
     * Az adott adómértékhez tartozó értékesítés vagy szolgáltatásnyújtás nettó összege a számla pénznemében
     *
     * requirements: required
     * node name: vatRateNetAmount
     * xml type: xs:decimal
     * simple type: MonetaryType
     * pattern: 

     */
    protected $netAmount;
    
    /**
     * setter for netAmount
     *
     * @param mixed 
     * @return self
     */
    public function setNetAmount($value)
    {
        $this->netAmount = $value;
        return $this;
    }
    
    /**
     * getter for netAmount
     * 
     * @return mixed return value for 
     */
    public function getNetAmount()
    {
        return $this->netAmount;
    }
    
    /*
     * Az adott adómértékhez tartozó értékesítés vagy szolgáltatásnyújtás nettó összege forintban kifejezve.
     *
     * requirements: required
     * node name: vatRateNetAmount
     * xml type: xs:decimal
     * simple type: MonetaryType
     * pattern: 

     */
    protected $netAmountHUF;
    
    /**
     * setter for netAmount
     *
     * @param mixed 
     * @return self
     */
    public function setNetAmountHUF($value)
    {
        $this->netAmountHUF = $value;
        return $this;
    }
    
    /**
     * getter for netAmount
     * 
     * @return mixed return value for 
     */
    public function getNetAmountHUF()
    {
        return $this->netAmountHUF;
    }
    
    /*
     * Az adott adómértékhez tartozó értékesítés vagy szolgáltatásnyújtás ÁFA összege a számla pénznemében
     *
     * requirements: required
     * node name: vatRateVatAmount
     * xml type: xs:decimal
     * simple type: MonetaryType
     * pattern: 

     */
    protected $vatAmount;
    
    /**
     * setter for vatAmount
     *
     * @param mixed 
     * @return self
     */
    public function setVatAmount($value)
    {
        $this->vatAmount = $value;
        return $this;
    }
    
    /**
     * getter for vatAmount
     * 
     * @return mixed return value for 
     */
    public function getVatAmount()
    {
        return $this->vatAmount;
    }
    
    /*
     * Az adott adómértékhez tartozó értékesítés vagy szolgáltatásnyújtás ÁFA összege forintban
     *
     * requirements: not required
     * node name: vatRateVatAmountHUF
     * xml type: xs:decimal
     * simple type: MonetaryType
     * pattern: 

     */
    protected $vatAmountHUF;
    
    /**
     * setter for vatAmountHUF
     *
     * @param mixed 
     * @return self
     */
    public function setVatAmountHUF($value)
    {
        $this->vatAmountHUF = $value;
        return $this;
    }
    
    /**
     * getter for vatAmountHUF
     * 
     * @return mixed return value for 
     */
    public function getVatAmountHUF()
    {
        return $this->vatAmountHUF;
    }
    
    /*
     * Az adott adómértékhez tartozó értékesítés vagy szolgáltatásnyújtás bruttó összege a számla pénznemében 
     *
     * requirements: not required
     * node name: vatRateGrossAmount
     * xml type: xs:decimal
     * simple type: MonetaryType
     * pattern: 

     */
    protected $grossAmount;
    
    /**
     * setter for grossAmount
     *
     * @param mixed 
     * @return self
     */
    public function setGrossAmount($value)
    {
        $this->grossAmount = $value;
        return $this;
    }
    
    /**
     * getter for grossAmount
     * 
     * @return mixed return value for 
     */
    public function getGrossAmount()
    {
        return $this->grossAmount;
    }
    
    /*
     * Az adott adómértékhez tartozó értékesítés vagy szolgáltatásnyújtás bruttó összege forintban kifejezve.
     *
     * requirements: not required
     * node name: vatRateGrossAmountHUF
     * xml type: xs:decimal
     * simple type: MonetaryType
     * pattern: 

     */
    protected $grossAmountHUF;
    
    /**
     * setter for grossAmount
     *
     * @param mixed 
     * @return self
     */
    public function setGrossAmountHUF($value)
    {
        $this->grossAmountHUF = $value;
        return $this;
    }
    
    /**
     * getter for grossAmount
     * 
     * @return mixed return value for 
     */
    public function getGrossAmountHUF()
    {
        return $this->grossAmountHUF;
    }
}
