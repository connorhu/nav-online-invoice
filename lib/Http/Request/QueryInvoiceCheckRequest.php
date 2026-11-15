<?php

namespace NAV\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\RequestServiceKindEnum;

/**
 * POST /queryInvoiceCheck: checks whether a valid invoice with the given number exists,
 * without returning its data.
 */
class QueryInvoiceCheckRequest extends Request implements HeaderAwareRequest, UserAwareRequest, SoftwareAwareRequest, InvoiceNumberQueryRequest
{
    use HeaderAwareTrait;
    use SoftwareAwareTrait;
    use UserAwareTrait;
    use InvoiceNumberQueryTrait;

    public const ROOT_NODE_NAME = 'QueryInvoiceCheckRequest';

    public function getEndpointPath(): string
    {
        return '/queryInvoiceCheck';
    }

    public function getServiceKind(): RequestServiceKindEnum
    {
        return RequestServiceKindEnum::InvoiceService;
    }
}
