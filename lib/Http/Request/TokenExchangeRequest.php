<?php

namespace NAV\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\Request;

class TokenExchangeRequest extends Request
{
    public function getRootNodeName()
    {
        return 'TokenExchangeRequest';
    }
    
    public function getEndpointPath()
    {
        return '/tokenExchange';
    }
}