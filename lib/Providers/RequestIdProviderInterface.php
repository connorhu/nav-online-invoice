<?php

namespace NAV\OnlineInvoice\Providers;

use NAV\OnlineInvoice\Http\Request\Software;

interface RequestIdProviderInterface
{
    public function getRequestId(): string;
}