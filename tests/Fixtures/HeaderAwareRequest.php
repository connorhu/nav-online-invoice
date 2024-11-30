<?php

namespace NAV\OnlineInvoice\Tests\Fixtures;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\HeaderAwareRequest as HeaderAwareRequestInterface;
use NAV\OnlineInvoice\Http\Request\HeaderAwareTrait;
use NAV\OnlineInvoice\Http\RequestServiceKindEnum;

class HeaderAwareRequest extends Request implements HeaderAwareRequestInterface
{
    use HeaderAwareTrait;

    public function getServiceKind(): RequestServiceKindEnum
    {
        return RequestServiceKindEnum::InvoiceService;
    }

    public function getEndpointPath(): string
    {
        return '';
    }
}
