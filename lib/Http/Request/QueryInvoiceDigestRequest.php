<?php

namespace NAV\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\RequestServiceKindEnum;

class QueryInvoiceDigestRequest extends Request implements HeaderAwareRequest, UserAwareRequest, SoftwareAwareRequest
{
    use HeaderAwareTrait;
    use SoftwareAwareTrait;
    use UserAwareTrait;

    public const ROOT_NODE_NAME = 'QueryInvoiceDigestRequest';
    
    public function getEndpointPath(): string
    {
        return '/queryInvoiceDigest';
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

    private ?\DateTime $issueDateFrom = null;

    private ?\DateTime $issueDateTo = null;

    private ?string $taxNumber = null;

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

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

    /**
     * @return \DateTime|null
     */
    public function getIssueDateFrom(): ?\DateTime
    {
        return $this->issueDateFrom;
    }

    /**
     * @param \DateTime|null $issueDateFrom
     */
    public function setIssueDateFrom(?\DateTime $issueDateFrom): void
    {
        $this->issueDateFrom = $issueDateFrom;
    }

    /**
     * @return \DateTime|null
     */
    public function getIssueDateTo(): ?\DateTime
    {
        return $this->issueDateTo;
    }

    /**
     * @param \DateTime|null $issueDateTo
     */
    public function setIssueDateTo(?\DateTime $issueDateTo): void
    {
        $this->issueDateTo = $issueDateTo;
    }

    /**
     * @return string|null
     */
    public function getTaxNumber(): ?string
    {
        return $this->taxNumber;
    }

    /**
     * @param string|null $taxNumber
     */
    public function setTaxNumber(?string $taxNumber): void
    {
        $this->taxNumber = $taxNumber;
    }


}
