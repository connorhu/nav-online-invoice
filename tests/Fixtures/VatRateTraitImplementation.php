<?php

namespace NAV\OnlineInvoice\Tests\Fixtures;

use NAV\OnlineInvoice\Model\Interfaces\VatRateInterface;
use NAV\OnlineInvoice\Model\Traits\VatRateTrait;

class VatRateTraitImplementation implements VatRateInterface
{
    use VatRateTrait;
}
