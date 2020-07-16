<?php

namespace NAV\OnlineInvoice\Entity\Interfaces;

interface VatRateSummaryInterface extends VatRateInterface
{
    public function setNetAmount($value);
    public function getNetAmount();
    public function setNetAmountHUF($value);
    public function getNetAmountHUF();
    public function setVatAmount($value);
    public function getVatAmount();
    public function setVatAmountHUF($value);
    public function getVatAmountHUF();
    public function setGrossAmount($value);
    public function getGrossAmount();
    public function setGrossAmountHUF($value);
    public function getGrossAmountHUF();
}
