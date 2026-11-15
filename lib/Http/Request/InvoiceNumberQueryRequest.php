<?php

namespace NAV\OnlineInvoice\Http\Request;

/**
 * A request whose content is an invoiceNumberQuery (InvoiceNumberQueryType, invoiceApi.xsd v3.0):
 * QueryInvoiceDataRequest and QueryInvoiceCheckRequest.
 */
interface InvoiceNumberQueryRequest
{
    public const INVOICE_DIRECTION_INBOUND = 'INBOUND';
    public const INVOICE_DIRECTION_OUTBOUND = 'OUTBOUND';

    public function getInvoiceNumber(): string;

    public function getInvoiceDirection(): string;

    public function getBatchIndex(): ?int;

    public function getSupplierTaxNumber(): ?string;
}
