<?php

namespace NAV\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\Request;

class TokenExchangeRequest extends Request implements HeaderAwareRequest, UserAwareRequest, SoftwareAwareRequest, InvoiceService
{
    use HeaderAwareTrait;
    use SoftwareAwareTrait;
    use UserAwareTrait;
    
    public const ROOT_NODE_NAME = 'TokenExchangeRequest';
    
    public function getEndpointPath(): string
    {
        return '/tokenExchange';
    }
}