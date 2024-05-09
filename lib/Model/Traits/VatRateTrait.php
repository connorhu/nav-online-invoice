<?php

namespace NAV\OnlineInvoice\Model\Traits;

use NAV\OnlineInvoice\Model\Enums\VatRateAmountMismatchCase;
use NAV\OnlineInvoice\Model\Enums\VatRateExemptionCase;
use NAV\OnlineInvoice\Model\Enums\VatRateOutOfScopeCase;
use NAV\OnlineInvoice\Model\Interfaces\VatRateInterface;

trait VatRateTrait
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

    protected ?float $vatRateContent = null;

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
    protected ?VatRateExemptionCase $vatRateExemptionCase = null;

    /*
     * Az adómentesség jelölés leírása
     */
    protected ?string $vatRateExemptionReason = null;

    protected ?VatRateOutOfScopeCase $vatRateOutOfScopeCase = null;

    protected ?string $vatRateOutOfScopeReason = null;

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

    protected ?bool $vatRateMarginSchemeIndicator = null;

    protected ?float $vatRateAmountMismatchRate = null;

    protected ?VatRateAmountMismatchCase $vatRateAmountMismatchCase = null;

    protected ?bool $vatRateNoVatCharge = null;

    public function getVatRatePercentage(): ?float
    {
        return $this->vatRatePercentage;
    }

    public function setVatRatePercentage(?float $vatRatePercentage): VatRateInterface
    {
        $this->vatRatePercentage = $vatRatePercentage;

        return $this;
    }

    public function getVatRateContent(): ?float
    {
        return $this->vatRateContent;
    }

    public function setVatRateContent(?float $vatRateContent): VatRateInterface
    {
        $this->vatRateContent = $vatRateContent;

        return $this;
    }

    public function getVatRateExemptionCase(): ?VatRateExemptionCase
    {
        return $this->vatRateExemptionCase;
    }

    public function setVatRateExemptionCase(?VatRateExemptionCase $vatRateExemptionCase): VatRateInterface
    {
        $this->vatRateExemptionCase = $vatRateExemptionCase;

        return $this;
    }

    public function getVatRateExemptionReason(): ?string
    {
        return $this->vatRateExemptionReason;
    }

    public function setVatRateExemptionReason(?string $vatRateExemptionReason): VatRateInterface
    {
        $this->vatRateExemptionReason = $vatRateExemptionReason;

        return $this;
    }

    public function getVatRateOutOfScopeCase(): ?VatRateOutOfScopeCase
    {
        return $this->vatRateOutOfScopeCase;
    }

    public function setVatRateOutOfScopeCase(?VatRateOutOfScopeCase $vatRateOutOfScopeCase): VatRateInterface
    {
        $this->vatRateOutOfScopeCase = $vatRateOutOfScopeCase;

        return $this;
    }

    public function getVatRateOutOfScopeReason(): ?string
    {
        return $this->vatRateOutOfScopeReason;
    }

    public function setVatRateOutOfScopeReason(?string $vatRateOutOfScopeReason): VatRateInterface
    {
        $this->vatRateOutOfScopeReason = $vatRateOutOfScopeReason;

        return $this;
    }

    public function getVatRateDomesticReverseCharge(): ?bool
    {
        return $this->vatRateDomesticReverseCharge;
    }

    public function setVatRateDomesticReverseCharge(?bool $vatRateDomesticReverseCharge): VatRateInterface
    {
        $this->vatRateDomesticReverseCharge = $vatRateDomesticReverseCharge;

        return $this;
    }

    public function getVatRateMarginSchemeIndicator(): ?bool
    {
        return $this->vatRateMarginSchemeIndicator;
    }

    public function setVatRateMarginSchemeIndicator(?bool $vatRateMarginSchemeIndicator): VatRateInterface
    {
        $this->vatRateMarginSchemeIndicator = $vatRateMarginSchemeIndicator;

        return $this;
    }

    public function getVatRateAmountMismatchRate(): ?float
    {
        return $this->vatRateAmountMismatchRate;
    }

    public function setVatRateAmountMismatchRate(?float $vatRateAmountMismatchRate): VatRateInterface
    {
        $this->vatRateMarginSchemeIndicator = $vatRateAmountMismatchRate;

        return $this;
    }

    public function getVatRateAmountMismatchCase(): ?VatRateAmountMismatchCase
    {
        return $this->vatRateAmountMismatchCase;
    }

    public function setVatRateAmountMismatchCase(?VatRateAmountMismatchCase $vatRateAmountMismatchCase): VatRateInterface
    {
        $this->vatRateMarginSchemeIndicator = $vatRateAmountMismatchCase;

        return $this;
    }

    public function getVatRateNoVatCharge(): ?bool
    {
        return $this->vatRateNoVatCharge;

    }

    public function setVatRateNoVatCharge(?bool $vatRateMarginSchemeNoVat): VatRateInterface
    {
        $this->vatRateMarginSchemeIndicator = $vatRateMarginSchemeNoVat;

        return $this;
    }
}

class_alias(VatRateTrait::class, \NAV\OnlineInvoice\Model\VatRate::class);
