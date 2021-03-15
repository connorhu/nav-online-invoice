<?php

namespace NAV\Tests\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\HeaderAwareRequest as HeaderAwareRequestInterface;
use NAV\OnlineInvoice\Http\Request\HeaderAwareTrait;

class HeaderAwareRequest extends Request implements HeaderAwareRequestInterface
{
    use HeaderAwareTrait;
    
    public function getEndpointPath(): string
    {
        return '';
    }
}
