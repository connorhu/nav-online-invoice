<?php

namespace NAV\OnlineInvoice\Entity\Interfaces;

interface VatRateInterface
{
    public function setVatRatePercentage($value);
    public function getVatRatePercentage();
    public function setVatRateExemption($value);
    public function getVatRateExemption();
    public function setVatRateOutOfScope($value);
    public function getVatRateOutOfScope();
    public function setVatRateDomesticReverseCharge($value);
    public function getVatRateDomesticReverseCharge();
    public function setVatRateMarginSchemeVat($value);
    public function getVatRateMarginSchemeVat();
    public function setVatRateMarginSchemeNoVat($value);
    public function getVatRateMarginSchemeNoVat();
}

