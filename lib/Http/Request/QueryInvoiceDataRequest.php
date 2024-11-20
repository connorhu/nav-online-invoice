<?php

namespace NAV\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\RequestServiceKindEnum;

class QueryInvoiceDataRequest extends Request implements HeaderAwareRequest, UserAwareRequest, SoftwareAwareRequest
{
    use HeaderAwareTrait;
    use SoftwareAwareTrait;
    use UserAwareTrait;
    
    public const ROOT_NODE_NAME = 'QueryInvoiceDataRequest';
    
    public function getEndpointPath(): string
    {
        return '/queryInvoiceData';
    }

    public function getServiceKind(): RequestServiceKindEnum
    {
        return RequestServiceKindEnum::InvoiceService;
    }

    private string $invoiceNumber;

    const INVOICE_DIRECTION_INBOUND = 'INBOUND';
    const INVOICE_DIRECTION_OUTBOUND = 'OUTBOUND';

    private string $invoiceDirection = self::INVOICE_DIRECTION_OUTBOUND;

    /**
     * @return string
     */
    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    /**
     * @param string $invoiceNumber
     */
    public function setInvoiceNumber(string $invoiceNumber): void
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    /**
     * @return string
     */
    public function getInvoiceDirection(): string
    {
        return $this->invoiceDirection;
    }

    /**
     * @param string $invoiceDirection
     */
    public function setInvoiceDirection(string $invoiceDirection): void
    {
        $this->invoiceDirection = $invoiceDirection;
    }


}
