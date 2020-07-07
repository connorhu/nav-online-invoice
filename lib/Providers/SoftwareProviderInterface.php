<?php

namespace NAV\OnlineInvoice\Providers;

use NAV\OnlineInvoice\Http\Request\Software;

interface SoftwareProviderInterface
{
    public function getSoftware(): Software;
}