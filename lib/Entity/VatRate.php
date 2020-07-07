<?php

namespace NAV\OnlineInvoice\Entity;

use NAV\OnlineInvoice\Serialize\XMLSerialize;

trait VatRate
{
    /*
     * Adómérték vagy adómentesség jelölése.
     *
     * Az alkalmazott adó mértéke - ÁFA tv. 169. § j)
     *
     * requirements: required
     * node name: lineVatRate / vatPercentage
     * xml type: VatRateType / xs:decimal
     * simple type: VatRateType / RateType
     * pattern: -
        minInclusive value="0"
        maxInclusive value="1"
        totalDigits value="5"
        fractionDigits value="4"

<line>
	<lineAmountsNormal>
		<lineVatRate>
			<vatPercentage>0.05</vatPercentage>
     */
    protected $vatRatePercentage;
    
    /**
     * setter for vatRatePercentage
     *
     * @param mixed 
     * @return self
     */
    public function setVatRatePercentage($value)
    {
        $this->vatRatePercentage = $value;
        return $this;
    }
    
    /**
     * getter for vatRatePercentage
     * 
     * @return mixed return value for 
     */
    public function getVatRatePercentage()
    {
        return $this->vatRatePercentage;
    }
    
    /*
     * Az adómentesség jelölése - ÁFA tv. 169. § m) 
     *
     * requirements: required
     * node name: lineVatRate / vatExemption
     * xml type: xs:string
     * simple type: SimpleText50NotBlankType
     * pattern: .*[^\s].*

<line>
	<lineAmountsNormal>
		<lineNetAmount>600000.00</lineNetAmount>
		<vatExemption>...</vatExemption>
     */
    protected $vatRateExemption;
    
    /**
     * setter for vatRateExemption
     *
     * @param mixed 
     * @return self
     */
    public function setVatRateExemption($value)
    {
        $this->vatRateExemption = $value;
        return $this;
    }
    
    /**
     * getter for vatRateExemption
     * 
     * @return mixed return value for 
     */
    public function getVatRateExemption()
    {
        return $this->vatRateExemption;
    }
    
    /*
     * Az ÁFA törvény hatályán kívüli.
     *
     * requirements: required
     * node name: lineVatRate / vatOutOfScope
     * xml type: xs:boolean
     * simple type: boolean
     * pattern: 

     */
    protected $vatRateOutOfScope;
    
    /**
     * setter for vatRateOutOfScope
     *
     * @param mixed 
     * @return self
     */
    public function setVatRateOutOfScope($value)
    {
        $this->vatRateOutOfScope = $value;
        return $this;
    }
    
    /**
     * getter for vatRateOutOfScope
     * 
     * @return mixed return value for 
     */
    public function getVatRateOutOfScope()
    {
        return $this->vatRateOutOfScope;
    }
    
    /*
     * A belföldi fordított adózás jelölése - ÁFA tv. 142. §
     *
     * requirements: required
     * node name: lineVatRate / vatDomesticReverseCharge
     * xml type: xs:boolean
     * simple type: boolean
     * pattern: 

     */
    protected $vatRateDomesticReverseCharge;
    
    /**
     * setter for vatRateDomesticReverseCharge
     *
     * @param mixed 
     * @return self
     */
    public function setVatRateDomesticReverseCharge($value)
    {
        $this->vatRateDomesticReverseCharge = $value;
        return $this;
    }
    
    /**
     * getter for vatRateDomesticReverseCharge
     * 
     * @return mixed return value for 
     */
    public function getVatRateDomesticReverseCharge()
    {
        return $this->vatRateDomesticReverseCharge;
    }
    
    /*
     * Áthárított adót tartalmazó különbözet szerinti adózásra
     *
     * requirements: required
     * node name: lineVatRate / marginSchemeVat
     * xml type: xs:boolean
     * simple type: boolean
     * pattern: 

     */
    protected $vatRateMarginSchemeVat;
    
    /**
     * setter for vatRateMarginSchemeVat
     *
     * @param mixed 
     * @return self
     */
    public function setVatRateMarginSchemeVat($value)
    {
        $this->vatRateMarginSchemeVat = $value;
        return $this;
    }
    
    /**
     * getter for vatRateMarginSchemeVat
     * 
     * @return mixed return value for 
     */
    public function getVatRateMarginSchemeVat()
    {
        return $this->vatRateMarginSchemeVat;
    }
    
    /*
     * Áthárított adót nem tartalmazó különbözet szerinti adózásra
     *
     * requirements: required
     * node name: lineVatRate / marginSchemeNoVat
     * xml type: xs:boolean
     * simple type: boolean
     * pattern: 

     */
    protected $vatRateMarginSchemeNoVat;
    
    /**
     * setter for vatRateMarginSchemeNoVat
     *
     * @param mixed 
     * @return self
     */
    public function setVatRateMarginSchemeNoVat($value)
    {
        $this->vatRateMarginSchemeNoVat = $value;
        return $this;
    }
    
    /**
     * getter for vatRateMarginSchemeNoVat
     * 
     * @return mixed return value for 
     */
    public function getVatRateMarginSchemeNoVat()
    {
        return $this->vatRateMarginSchemeNoVat;
    }
    
    protected function serializeVatRate()
    {
        $buffer = [];
        
        $buffer['vatPercentage'] = $this->vatRatePercentage;

        if ($this->vatRateExemption) {
            $buffer['vatExemption'] = $this->vatRateExemption;
        }

        if ($this->vatRateOutOfScope) {
            $buffer['vatOutOfScope'] = XMLSerialize::formatBoolean($this->vatRateOutOfScope);
        }

        if ($this->vatRateDomesticReverseCharge) {
            $buffer['vatDomesticReverseCharge'] = XMLSerialize::formatBoolean($this->vatRateDomesticReverseCharge);
        }

        if ($this->vatRateMarginSchemeVat) {
            $buffer['marginSchemeVat'] = XMLSerialize::formatBoolean($this->vatRateMarginSchemeVat);
        }

        if ($this->vatRateMarginSchemeNoVat) {
            $buffer['marginSchemeNoVat'] = XMLSerialize::formatBoolean($this->vatRateMarginSchemeNoVat);
        }
        
        return $buffer;
    }
}

