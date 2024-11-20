<?php

namespace NAV\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\RequestServiceKindEnum;

class TokenExchangeRequest extends Request implements HeaderAwareRequest, UserAwareRequest, SoftwareAwareRequest
{
    use HeaderAwareTrait;
    use SoftwareAwareTrait;
    use UserAwareTrait;
    
    public const ROOT_NODE_NAME = 'TokenExchangeRequest';
    
    public function getEndpointPath(): string
    {
        return '/tokenExchange';
    }

    public function getServiceKind(): RequestServiceKindEnum
    {
        return RequestServiceKindEnum::InvoiceService;
    }
}
