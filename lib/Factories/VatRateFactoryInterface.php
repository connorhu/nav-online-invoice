<?php

namespace NAV\OnlineInvoice\Factories;

use NAV\OnlineInvoice\Model\Interfaces\VatRateInterface;

interface VatRateFactoryInterface
{
    public function createVatRate(): VatRateInterface;
}
