<?php

namespace NAV\OnlineInvoice\Entity;

use NAV\OnlineInvoice\Entity\Interfaces\VatRateInterface;

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
    protected ?float $vatRatePercentage = null;
    
    /**
     * setter for vatRatePercentage
     *
     * @param float|null $vatRatePercentage New value for vatRatePercentage field
     * @return VatRateInterface
     */
    public function setVatRatePercentage(?float $vatRatePercentage): VatRateInterface
    {
        $this->vatRatePercentage = $vatRatePercentage;

        return $this;
    }
    
    /**
     * getter for vatRatePercentage
     * 
     * @return float|null Return value for vatRatePercentage field
     */
    public function getVatRatePercentage(): ?float
    {
        return $this->vatRatePercentage;
    }
    
    /*
     * Az adómentesség jelölés kódja
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
    protected ?int $vatRateExemptionCase = null;
    
    /**
     * setter for vatRateExemption
     *
     * @param int|null $vatRateExemptionCase New Value for vatRateExemptionCase field
     * @return VatRateInterface
     */
    public function setVatRateExemptionCase(?int $vatRateExemptionCase): VatRateInterface
    {
        $this->vatRateExemptionCase = $vatRateExemptionCase;
        return $this;
    }
    
    /**
     * getter for vatRateExemption
     * 
     * @return int|null Return value of vatRateExemptionCase field
     */
    public function getVatRateExemptionCase(): ?int
    {
        return $this->vatRateExemptionCase;
    }

    public function getVatRateExemptionCaseString(): ?string
    {
        return match ($this->vatRateExemptionCase) {
            null => null,
            VatRateInterface::VAT_RATE_EXEMPTION_CASE_AAM => 'AAM',
            VatRateInterface::VAT_RATE_EXEMPTION_CASE_TAM => 'TAM',
            VatRateInterface::VAT_RATE_EXEMPTION_CASE_KBAET => 'KBAET',
            VatRateInterface::VAT_RATE_EXEMPTION_CASE_KBAUK => 'KBAUK',
            VatRateInterface::VAT_RATE_EXEMPTION_CASE_EAM => 'EAM',
            VatRateInterface::VAT_RATE_EXEMPTION_CASE_NAM => 'NAM',
            VatRateInterface::VAT_RATE_EXEMPTION_CASE_UNKNOWN => 'UNKNOWN',
        };
    }

    /*
     * Az adómentesség jelölés leírása
     */
    protected ?string $vatRateExemptionReason = null;

    /**
     * @param string|null $vatRateExemptionReason New value for vatRateExemptionReason field
     */
    public function setVatRateExemptionReason(?string $vatRateExemptionReason): VatRateInterface
    {
        $this->vatRateExemptionReason = $vatRateExemptionReason;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVatRateExemptionReason(): ?string
    {
        return $this->vatRateExemptionReason;
    }

    protected ?int $vatRateOutOfScopeCase = null;

    /**
     * setter for vatRateOutOfScopeCase
     *
     * @param int|null $vatRateOutOfScopeCase New value for Vat Rate Out of Scope Case
     * @return VatRateInterface
     */
    public function setVatRateOutOfScopeCase(?int $vatRateOutOfScopeCase): VatRateInterface
    {
        $this->vatRateOutOfScopeCase = $vatRateOutOfScopeCase;

        return $this;
    }

    /**
     * getter for vatRateOutOfScopeCase
     *
     * @return mixed return value for
     */
    public function getVatRateOutOfScopeCase(): ?int
    {
        return $this->vatRateOutOfScopeCase;
    }

    public function getVatRateOutOfScopeCaseString(): ?string
    {
        return match ($this->vatRateOutOfScopeCase) {
            null => null,
            VatRateInterface::VAT_RATE_OUT_OF_SCOPE_CASE_ATK => 'ATK',
            VatRateInterface::VAT_RATE_OUT_OF_SCOPE_CASE_EUFAD37 => 'EUFAD37',
            VatRateInterface::VAT_RATE_OUT_OF_SCOPE_CASE_EUFADE => 'EUFADE',
            VatRateInterface::VAT_RATE_OUT_OF_SCOPE_CASE_EUE => 'EUE',
            VatRateInterface::VAT_RATE_OUT_OF_SCOPE_CASE_HO => 'HO',
            VatRateInterface::VAT_RATE_OUT_OF_SCOPE_CASE_UNKNOWN => 'UNKNOWN',
        };
    }

    protected ?string $vatRateOutOfScopeReason = null;

    /**
     * setter for vatRateOutOfScopeReason
     *
     * @param string|null $vatRateOutOfScopeReason New value for vatRateOutOfScopeReason field
     * @return VatRateInterface
     */
    public function setVatRateOutOfScopeReason(?string $vatRateOutOfScopeReason): VatRateInterface
    {
        $this->vatRateOutOfScopeReason = $vatRateOutOfScopeReason;

        return $this;
    }

    /**
     * getter for vatRateOutOfScopeReason
     *
     * @return string|null return value for
     */
    public function getVatRateOutOfScopeReason(): ?string
    {
        return $this->vatRateOutOfScopeReason;
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
    protected ?bool $vatRateDomesticReverseCharge = null;
    
    /**
     * setter for vatRateDomesticReverseCharge
     *
     * @param mixed 
     * @return self
     */
    public function setVatRateDomesticReverseCharge(?bool $vatRateDomesticReverseCharge): VatRateInterface
    {
        $this->vatRateDomesticReverseCharge = $vatRateDomesticReverseCharge;
        return $this;
    }
    
    /**
     * getter for vatRateDomesticReverseCharge
     * 
     * @return mixed return value for 
     */
    public function getVatRateDomesticReverseCharge(): ?bool
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
    protected ?bool $vatRateMarginSchemeVat = null;
    
    /**
     * setter for vatRateMarginSchemeVat
     *
     * @param bool|null $vatRateMarginSchemeVat New value for vatRateMarginSchemeVat field
     * @return VatRateInterface
     */
    public function setVatRateMarginSchemeVat(?bool $vatRateMarginSchemeVat): VatRateInterface
    {
        $this->vatRateMarginSchemeVat = $vatRateMarginSchemeVat;
        return $this;
    }
    
    /**
     * getter for vatRateMarginSchemeVat
     * 
     * @return bool|null return value for
     */
    public function getVatRateMarginSchemeVat(): ?bool
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
    protected ?bool $vatRateMarginSchemeNoVat = null;
    
    /**
     * setter for vatRateMarginSchemeNoVat
     *
     * @param bool|null $vatRateMarginSchemeNoVat New value for vatRateMarginSchemeNoVat field
     * @return VatRateInterface
     */
    public function setVatRateMarginSchemeNoVat(?bool $vatRateMarginSchemeNoVat): VatRateInterface
    {
        $this->vatRateMarginSchemeNoVat = $vatRateMarginSchemeNoVat;
        return $this;
    }
    
    /**
     * getter for vatRateMarginSchemeNoVat
     * 
     * @return bool|null return value for
     */
    public function getVatRateMarginSchemeNoVat(): ?bool
    {
        return $this->vatRateMarginSchemeNoVat;
    }
}

