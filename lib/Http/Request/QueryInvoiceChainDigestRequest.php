<?php

namespace NAV\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\RequestServiceKindEnum;

class QueryInvoiceChainDigestRequest extends Request implements HeaderAwareRequest, UserAwareRequest, SoftwareAwareRequest
{
    use HeaderAwareTrait;
    use SoftwareAwareTrait;
    use UserAwareTrait;
    
    public const ROOT_NODE_NAME = 'QueryInvoiceChainDigestRequest';
    
    public function getEndpointPath(): string
    {
        return '/queryInvoiceChainDigest';
    }

    public function getServiceKind(): RequestServiceKindEnum
    {
        return RequestServiceKindEnum::InvoiceService;
    }

    private int $page = 1;

    private string $invoiceNumber;

    const INVOICE_DIRECTION_INBOUND = 'INBOUND';
    const INVOICE_DIRECTION_OUTBOUND = 'OUTBOUND';

    private string $invoiceDirection = self::INVOICE_DIRECTION_OUTBOUND;


}
