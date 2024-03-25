<?php

namespace NAV\OnlineInvoice\Model\Interfaces;

interface VatRateInterface
{
    public function setVatRatePercentage(?float $vatRatePercentage): VatRateInterface;
    public function getVatRatePercentage(): ?float;

    public const VAT_RATE_EXEMPTION_CASE_AAM = 1;
    public const VAT_RATE_EXEMPTION_CASE_TAM = 2;
    public const VAT_RATE_EXEMPTION_CASE_KBAET = 3;
    public const VAT_RATE_EXEMPTION_CASE_KBAUK = 4;
    public const VAT_RATE_EXEMPTION_CASE_EAM = 5;
    public const VAT_RATE_EXEMPTION_CASE_NAM = 6;
    public const VAT_RATE_EXEMPTION_CASE_UNKNOWN = 7;

    public function setVatRateExemptionCase(?int $vatRateExemptionCase): VatRateInterface;
    public function getVatRateExemptionCase(): ?int;
    public function getVatRateExemptionCaseString(): ?string;

    public function setVatRateExemptionReason(?string $vatRateExemptionReason): VatRateInterface;
    public function getVatRateExemptionReason(): ?string;

    public const VAT_RATE_OUT_OF_SCOPE_CASE_ATK = 1;
    public const VAT_RATE_OUT_OF_SCOPE_CASE_EUFAD37 = 2;
    public const VAT_RATE_OUT_OF_SCOPE_CASE_EUFADE = 3;
    public const VAT_RATE_OUT_OF_SCOPE_CASE_EUE = 4;
    public const VAT_RATE_OUT_OF_SCOPE_CASE_HO = 5;
    public const VAT_RATE_OUT_OF_SCOPE_CASE_UNKNOWN = 6;

    public function setVatRateOutOfScopeCase(?int $vatRateOutOfScopeCase): VatRateInterface;
    public function getVatRateOutOfScopeCase(): ?int;

    public function setVatRateOutOfScopeReason(?string $vatRateOutOfScopeReason): VatRateInterface;
    public function getVatRateOutOfScopeReason(): ?string;

    public function setVatRateDomesticReverseCharge(?bool $vatRateDomesticReverseCharge): VatRateInterface;
    public function getVatRateDomesticReverseCharge(): ?bool;
    public function setVatRateMarginSchemeVat(?bool $vatRateMarginSchemeVat): VatRateInterface;
    public function getVatRateMarginSchemeVat(): ?bool;
    public function setVatRateMarginSchemeNoVat(?bool $vatRateMarginSchemeNoVat): VatRateInterface;
    public function getVatRateMarginSchemeNoVat(): ?bool;
}

class_alias(VatRateInterface::class, \NAV\OnlineInvoice\Model\Interfaces\VatRateInterface::class);
