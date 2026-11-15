<?php

namespace NAV\OnlineInvoice\Model;

use NAV\OnlineInvoice\Model\Enums\InvoiceAppearanceEnum;
use NAV\OnlineInvoice\Model\Enums\InvoiceOperationEnum;
use NAV\OnlineInvoice\Model\Enums\PaymentMethodEnum;

/**
 * One invoiceDigest element of a QueryInvoiceDigestResponse (InvoiceDigestType, invoiceApi.xsd v3.0).
 */
class InvoiceDigest
{
    public string $invoiceNumber;

    public ?int $batchIndex = null;

    public InvoiceOperationEnum $invoiceOperation;

    /**
     * NORMAL, SIMPLIFIED or AGGREGATE
     */
    public string $invoiceCategory;

    public \DateTimeImmutable $invoiceIssueDate;

    /**
     * 8 digit taxpayer id
     */
    public string $supplierTaxNumber;

    public ?string $supplierGroupMemberTaxNumber = null;

    public string $supplierName;

    public ?string $customerTaxNumber = null;

    public ?string $customerGroupMemberTaxNumber = null;

    public ?string $customerName = null;

    public ?PaymentMethodEnum $paymentMethod = null;

    public ?\DateTimeImmutable $paymentDate = null;

    public ?InvoiceAppearanceEnum $invoiceAppearance = null;

    /**
     * WEB, XML, MGM or OPG
     */
    public ?string $source = null;

    public ?\DateTimeImmutable $invoiceDeliveryDate = null;

    public ?string $currency = null;

    public ?float $invoiceNetAmount = null;

    public ?float $invoiceNetAmountHUF = null;

    public ?float $invoiceVatAmount = null;

    public ?float $invoiceVatAmountHUF = null;

    public ?string $transactionId = null;

    public ?int $index = null;

    public ?string $originalInvoiceNumber = null;

    public ?int $modificationIndex = null;

    /**
     * Time the invoice was received by NAV (UTC)
     */
    public \DateTimeImmutable $insDate;

    public ?bool $completenessIndicator = null;
}
