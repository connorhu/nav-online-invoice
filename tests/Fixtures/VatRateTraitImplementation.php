<?php

namespace NAV\Tests\OnlineInvoice\Fixtures;

use NAV\OnlineInvoice\Entity\Interfaces\VatRateInterface;
use NAV\OnlineInvoice\Entity\VatRate;

class VatRateTraitImplementation implements VatRateInterface
{
    use VatRate;
}
