<?php

namespace NAV\OnlineInvoice\Http\Response;

use NAV\OnlineInvoice\Model\Interfaces\InvoiceInterface;
use NAV\OnlineInvoice\Http\Request\Header;
use NAV\OnlineInvoice\Http\Request\Software;
use NAV\OnlineInvoice\Http\Response;

class QueryInvoiceDataResponse extends Response
{
    protected Header $header;

    protected Software $software;

    protected Audit $audit;

    protected bool $compressedContentIndicator = false;

    protected ?InvoiceInterface $invoiceData = null;

    /**
     * @param Header $header
     */
    public function setHeader(Header $header): void
    {
        $this->header = $header;
    }

    /**
     * @return Header
     */
    public function getHeader(): Header
    {
        return $this->header;
    }

    /**
     * @param Software $software
     */
    public function setSoftware(Software $software): void
    {
        $this->software = $software;
    }

    /**
     * @return Software
     */
    public function getSoftware(): Software
    {
        return $this->software;
    }

    /**
     * @param Audit $audit
     */
    public function setAudit(Audit $audit): void
    {
        $this->audit = $audit;
    }

    /**
     * @return Audit
     */
    public function getAudit(): Audit
    {
        return $this->audit;
    }

    /**
     * @param bool $compressedContentIndicator
     */
    public function setCompressedContentIndicator(bool $compressedContentIndicator): void
    {
        $this->compressedContentIndicator = $compressedContentIndicator;
    }

    /**
     * @return bool
     */
    public function getCompressedContentIndicator(): bool
    {
        return $this->compressedContentIndicator;
    }

    /**
     * @param InvoiceInterface $invoiceData
     */
    public function setInvoiceData(InvoiceInterface $invoiceData): void
    {
        $this->invoiceData = $invoiceData;
    }

    /**
     * @return InvoiceInterface
     */
    public function getInvoiceData(): InvoiceInterface
    {
        return $this->invoiceData;
    }
}
