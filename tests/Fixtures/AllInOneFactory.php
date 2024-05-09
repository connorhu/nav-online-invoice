<?php

namespace NAV\OnlineInvoice\Tests\Fixtures;

use NAV\OnlineInvoice\Factories\VatRateFactoryInterface;
use NAV\OnlineInvoice\Model\Interfaces\VatRateInterface;

class AllInOneFactory implements VatRateFactoryInterface
{
    public VatRateInterface $vatRate;

    public function __construct()
    {
        $this->vatRate = new VatRateTraitImplementation();
    }

    public function createVatRate(): VatRateInterface
    {
        return $this->vatRate;
    }
}
