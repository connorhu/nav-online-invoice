<?php

namespace NAV\OnlineInvoice\Providers;

use NAV\OnlineInvoice\Http\Request;

interface ApiEndpointUrlProviderInterface
{
    public function getEndpointUrl(Request $request): string;
}