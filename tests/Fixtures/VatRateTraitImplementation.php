<?php

namespace NAV\OnlineInvoice\Tests\Fixtures;

use NAV\OnlineInvoice\Entity\Interfaces\VatRateInterface;
use NAV\OnlineInvoice\Entity\VatRate;

class VatRateTraitImplementation implements VatRateInterface
{
    use VatRate;
}
