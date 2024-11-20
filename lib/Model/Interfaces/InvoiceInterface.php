<?php

namespace NAV\OnlineInvoice\Model\Interfaces;

use Doctrine\Common\Collections\Collection;
use NAV\OnlineInvoice\Model\Address;

interface InvoiceInterface
{
    public function getSupplierTaxNumber(): string;
    public function setSupplierTaxNumber(string $supplierTaxNumber): InvoiceInterface;

    public function getSupplierGroupMemberTaxNumber(): ?string;
    public function setSupplierGroupMemberTaxNumber(?string $supplierGroupMemberTaxNumber): InvoiceInterface;

    public function getSupplierCommunityVatNumber(): ?string;
    public function setSupplierCommunityVatNumber(?string $supplierCommunityVatNumber): InvoiceInterface;

    public function getSupplierName(): string;
    public function setSupplierName(string $supplierName): InvoiceInterface;

    public function getSupplierAddress(): Address;
    public function setSupplierAddress(Address $supplierAddress): InvoiceInterface;

    public function getSupplierBankAccountNumber(): ?string;
    public function setSupplierBankAccountNumber(?string $supplierBankAccountNumber): InvoiceInterface;

    public const CUSTOMER_VAT_STATUS_DOMESTIC = 1;
    public const CUSTOMER_VAT_STATUS_OTHER = 2;
    public const CUSTOMER_VAT_STATUS_PRIVATE_PERSON = 3;

    public function getCustomerVatStatus(): int;
    public function getCustomerVatStatusString(): string;
    public function setCustomerVatStatus(int $customerVatStatus): InvoiceInterface;

    public function setCustomerTaxNumber(string $customerTaxNumber): InvoiceInterface;
    public function getCustomerTaxNumber(): ?string;
    public function getThirdStateTaxId(): ?string;
    public function setThirdStateTaxId(?string $thirdStateTaxId): InvoiceInterface;
    public function setCustomerGroupMemberTaxNumber(?string $customerGroupMemberTaxNumber): InvoiceInterface;
    public function getCustomerGroupMemberTaxNumber(): ?string;
    public function setCustomerCommunityVatNumber(?string $customerCommunityVatNumber): InvoiceInterface;
    public function getCustomerCommunityVatNumber(): ?string;

    public function setCustomerName(?string $customerName): InvoiceInterface;
    public function getCustomerName(): ?string;

    public function setCustomerAddress(?AddressInterface $customerAddress): InvoiceInterface;
    public function getCustomerAddress(): ?AddressInterface;

    public function addItem(InvoiceItemInterface $item): InvoiceInterface;
    public function removeItem(InvoiceItemInterface $item): InvoiceInterface;
    public function getItems(): Collection;

    public function addVatRateSummary(VatRateSummaryInterface $vatRateSummary): InvoiceInterface;
    public function removeVatRateSummary(VatRateSummaryInterface $vatRateSummary): InvoiceInterface;
    public function getVatRateSummaries(): Collection;
}
