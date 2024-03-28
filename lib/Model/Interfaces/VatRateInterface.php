<?php

namespace NAV\OnlineInvoice\Model\Interfaces;

use NAV\OnlineInvoice\Model\VatRateAmountMismatchCase;
use NAV\OnlineInvoice\Model\VatRateExemptionCase;
use NAV\OnlineInvoice\Model\VatRateOutOfScopeCase;

interface VatRateInterface
{
    /**
     * Applied VAT rate - Section 169 j) of the VAT Act
     * Az alkalmazott adó mértéke - Áfa tv. 169. § j)
     *
     * @see VatRateType/vatPercentage
     *
     * @return float|null
     */
    public function getVatRatePercentage(): ?float;

    /**
     * Applied VAT rate - Section 169 j) of the VAT Act
     * Az alkalmazott adó mértéke - Áfa tv. 169. § j)
     *
     * @see VatRateType/vatPercentage
     *
     * @param float|null $vatPercentage
     * @return VatRateInterface
     */
    public function setVatRatePercentage(?float $vatPercentage): VatRateInterface;

    /**
     * VAT content for simplified invoice
     * Áfatartalom egyszerűsített számla esetén
     *
     * @see VatRateType/vatContent
     *
     * @return float|null
     */
    public function getVatRateContent(): ?float;

    /**
     * VAT content for simplified invoice
     * Áfatartalom egyszerűsített számla esetén
     *
     * @see VatRateType/vatContent
     *
     * @param float|null $vatContent
     * @return VatRateInterface
     */
    public function setVatRateContent(?float $vatContent): VatRateInterface;

    /**
     * Code indicating tax exemption
     * Az adómentesség jelölés kódja
     *
     * @see VatRateType/vatExemption/case
     *
     * @return int|VatRateExemptionCase|null
     */
    public function getVatRateExemptionCase(): int|VatRateExemptionCase|null;

    /**
     * Code indicating tax exemption
     * Az adómentesség jelölés kódja
     *
     * @see VatRateType/vatExemption/case
     *
     * @param int|VatRateExemptionCase|null $vatRateExemptionCase
     * @return VatRateInterface
     */
    public function setVatRateExemptionCase(int|VatRateExemptionCase|null $vatRateExemptionCase): VatRateInterface;

    /**
     * Reasoning for tax exemption
     * Az adómentesség jelölés leírása
     *
     * @see VatRateType/vatExemption/reason
     *
     * @return string|null
     */
    public function getVatRateExemptionReason(): ?string;

    /**
     * Reasoning for tax exemption
     * Az adómentesség jelölés leírása
     *
     * @see VatRateType/vatExemption/reason
     *
     * @param string|null $vatRateExemptionReason
     * @return VatRateInterface
     */
    public function setVatRateExemptionReason(?string $vatRateExemptionReason): VatRateInterface;

    /**
     * Code indicating exemption from the scope of the VAT Act
     * Az Áfa tv.y hatályán kívüliség kódja
     *
     * @see VatRateType/vatOutOfScope/case
     *
     * @return int|VatRateOutOfScopeCase|null
     */
    public function getVatRateOutOfScopeCase(): int|VatRateOutOfScopeCase|null;

    /**
     * Code indicating exemption from the scope of the VAT Act
     * Az Áfa tv.y hatályán kívüliség kódja
     *
     * @see VatRateType/vatOutOfScope/case
     *
     * @param int|VatRateOutOfScopeCase|null $vatRateOutOfScopeCase
     * @return VatRateInterface
     */
    public function setVatRateOutOfScopeCase(int|VatRateOutOfScopeCase|null $vatRateOutOfScopeCase): VatRateInterface;

    /**
     * Reasoning for exemption from the scope of the VAT Act
     * Az Áfa tv.hatályán kívüliség leírása
     *
     * @return string|null
     * @see VatRateType/vatOutOfScope/reason
     *
     */
    public function getVatRateOutOfScopeReason(): ?string;

    /**
     * Reasoning for exemption from the scope of the VAT Act
     * Az Áfa tv.hatályán kívüliség leírása
     *
     * @see VatRateType/vatOutOfScope/reason
     *
     * @param string|null $vatRateOutOfScopeReason
     * @return VatRateInterface
     */
    public function setVatRateOutOfScopeReason(?string $vatRateOutOfScopeReason): VatRateInterface;

    /**
     * Indicates domestic reverse charging - Section 142 of the VAT Act
     * A belföldi fordított adózás jelölése - Áfa tv. 142. §
     *
     * @see VatRateType/vatDomesticReverseCharge
     *
     * @return bool|null
     */
    public function getVatRateDomesticReverseCharge(): ?bool;

    /**
     * Indicates domestic reverse charging - Section 142 of the VAT Act
     * A belföldi fordított adózás jelölése - Áfa tv. 142. §
     *
     * @see VatRateType/vatDomesticReverseCharge
     *
     * @param bool|null $vatRateDomesticReverseCharge
     * @return VatRateInterface
     */
    public function setVatRateDomesticReverseCharge(?bool $vatRateDomesticReverseCharge): VatRateInterface;

    /**
     * Indication of a margin scheme regulation – Section 169 p) q) of the VAT Act
     * Különbözet szerinti szabályozás jelölése - Áfa tv. 169. § p) q)
     *
     * @see VatRateType/marginSchemeIndicator
     *
     * @return bool|null
     */
    public function getVatRateMarginSchemeIndicator(): ?bool;

    /**
     * Indication of a margin scheme regulation – Section 169 p) q) of the VAT Act
     * Különbözet szerinti szabályozás jelölése - Áfa tv. 169. § p) q)
     *
     * @see VatRateType/marginSchemeIndicator
     *
     * @param bool|null $vatRateMarginSchemeIndicator
     * @return VatRateInterface
     */
    public function setVatRateMarginSchemeIndicator(?bool $vatRateMarginSchemeIndicator): VatRateInterface;

    /**
     * TODO EN
     * Adómérték, adótartalom
     *
     * @return float|null
     *@see VatRateType/vatAmountMismatch/vatRate
     *
     */
    public function getVatRateAmountMismatchRate(): ?float;

    /**
     * TODO EN
     * Adómérték, adótartalom
     *
     * @see VatRateType/vatAmountMismatch/vatRate
     *
     * @param float|null $vatRateAmountMismatchRate
     * @return VatRateInterface
     */
    public function setVatRateAmountMismatchRate(?float $vatRateAmountMismatchRate): VatRateInterface;

    /**
     * TODO EN
     * Adóalap és felszámított adó eltérésének kódja
     *
     * @see VatRateType/vatAmountMismatch/case
     *
     * @return mixed
     */
    public function getVatRateAmountMismatchCase(): ?VatRateAmountMismatchCase;

    /**
     * TODO EN
     * Adóalap és felszámított adó eltérésének kódja
     *
     * @see VatRateType/vatAmountMismatch/case
     *
     * @param mixed $vatRateAmountMismatchCase
     * @return VatRateInterface
     */
    public function setVatRateAmountMismatchCase(?VatRateAmountMismatchCase $vatRateAmountMismatchCase): VatRateInterface;

    /**
     * No VAT charged as per Section 17
     * Nincs felszámított áfa a 17. § alapján
     *
     * @see VatRateType/noVatCharge
     *
     * @return bool|null
     */
    public function getVatRateNoVatCharge(): ?bool;

    /**
     * No VAT charged as per Section 17
     * Nincs felszámított áfa a 17. § alapján
     *
     * @see VatRateType/noVatCharge
     *
     * @param bool|null $vatRateMarginSchemeNoVat
     * @return VatRateInterface
     */
    public function setVatRateNoVatCharge(?bool $vatRateMarginSchemeNoVat): VatRateInterface;

    /** Deprecated stuffs */

    /**
     * @deprecated
     * @see VatRateExemptionCase::toString
     */
    public function getVatRateExemptionCaseString(): ?string;

    /**
     * @deprecated
     * @see VatRateOutOfScopeCase
     */
    public const VAT_RATE_OUT_OF_SCOPE_CASE_ATK = 1;

    /**
     * @deprecated
     * @see VatRateOutOfScopeCase
     */
    public const VAT_RATE_OUT_OF_SCOPE_CASE_EUFAD37 = 2;

    /**
     * @deprecated
     * @see VatRateOutOfScopeCase
     */
    public const VAT_RATE_OUT_OF_SCOPE_CASE_EUFADE = 3;

    /**
     * @deprecated
     * @see VatRateOutOfScopeCase
     */
    public const VAT_RATE_OUT_OF_SCOPE_CASE_EUE = 4;

    /**
     * @deprecated
     * @see VatRateOutOfScopeCase
     */
    public const VAT_RATE_OUT_OF_SCOPE_CASE_HO = 5;

    /**
     * @deprecated
     * @see VatRateOutOfScopeCase
     */
    public const VAT_RATE_OUT_OF_SCOPE_CASE_UNKNOWN = 6;

    /**
     * @deprecated
     * @see VatRateExemptionCase
     */
    public const VAT_RATE_EXEMPTION_CASE_AAM = 1;

    /**
     * @deprecated
     * @see VatRateExemptionCase
     */
    public const VAT_RATE_EXEMPTION_CASE_TAM = 2;

    /**
     * @deprecated
     * @see VatRateExemptionCase
     */
    public const VAT_RATE_EXEMPTION_CASE_KBAET = 3;

    /**
     * @deprecated
     * @see VatRateExemptionCase
     */
    public const VAT_RATE_EXEMPTION_CASE_KBAUK = 4;

    /**
     * @deprecated
     * @see VatRateExemptionCase
     */
    public const VAT_RATE_EXEMPTION_CASE_EAM = 5;

    /**
     * @deprecated
     * @see VatRateExemptionCase
     */
    public const VAT_RATE_EXEMPTION_CASE_NAM = 6;

    /**
     * @deprecated
     * @see VatRateExemptionCase
     */
    public const VAT_RATE_EXEMPTION_CASE_UNKNOWN = 7;
}

class_alias(VatRateInterface::class, \NAV\OnlineInvoice\Model\Interfaces\VatRateInterface::class);
