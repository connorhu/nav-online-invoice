<?php

namespace NAV\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\RequestServiceKindEnum;

class QueryInvoiceDataRequest extends Request implements HeaderAwareRequest, UserAwareRequest, SoftwareAwareRequest, InvoiceNumberQueryRequest
{
    use HeaderAwareTrait;
    use SoftwareAwareTrait;
    use UserAwareTrait;
    use InvoiceNumberQueryTrait;

    public const ROOT_NODE_NAME = 'QueryInvoiceDataRequest';

    public function getEndpointPath(): string
    {
        return '/queryInvoiceData';
    }

    public function getServiceKind(): RequestServiceKindEnum
    {
        return RequestServiceKindEnum::InvoiceService;
    }
}
