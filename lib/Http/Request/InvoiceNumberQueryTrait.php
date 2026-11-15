<?php

namespace NAV\OnlineInvoice\Http\Request;

/**
 * Implements InvoiceNumberQueryRequest.
 */
trait InvoiceNumberQueryTrait
{
    private string $invoiceNumber;

    private string $invoiceDirection = InvoiceNumberQueryRequest::INVOICE_DIRECTION_OUTBOUND;

    private ?int $batchIndex = null;

    private ?string $supplierTaxNumber = null;

    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(string $invoiceNumber): void
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    /**
     * @return string InvoiceNumberQueryRequest::INVOICE_DIRECTION_*
     */
    public function getInvoiceDirection(): string
    {
        return $this->invoiceDirection;
    }

    /**
     * @param string $invoiceDirection InvoiceNumberQueryRequest::INVOICE_DIRECTION_*
     */
    public function setInvoiceDirection(string $invoiceDirection): void
    {
        $this->invoiceDirection = $invoiceDirection;
    }

    /**
     * Position of the modification document within a batch modification.
     */
    public function getBatchIndex(): ?int
    {
        return $this->batchIndex;
    }

    public function setBatchIndex(?int $batchIndex): void
    {
        $this->batchIndex = $batchIndex;
    }

    /**
     * The supplier's taxpayer id (first 8 digits of the tax number); only allowed for INBOUND queries.
     */
    public function getSupplierTaxNumber(): ?string
    {
        return $this->supplierTaxNumber;
    }

    /**
     * @param string|null $supplierTaxNumber a full tax number (12345678-2-41) is cut to its taxpayer id
     */
    public function setSupplierTaxNumber(?string $supplierTaxNumber): void
    {
        $this->supplierTaxNumber = $supplierTaxNumber === null ? null : substr($supplierTaxNumber, 0, 8);
    }
}
