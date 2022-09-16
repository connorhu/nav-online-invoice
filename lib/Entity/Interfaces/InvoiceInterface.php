<?php

namespace NAV\OnlineInvoice\Entity\Interfaces;

use NAV\OnlineInvoice\Entity\Address;

interface InvoiceInterface
{
    public function setSupplierTaxNumber(string $value): InvoiceInterface;
    public function getSupplierTaxNumber(): string;
    public function setSupplierGroupMemberTaxNumber(?string $value): InvoiceInterface;
    public function getSupplierGroupMemberTaxNumber(): ?string;
    public function setSupplierCommunityVatNumber(?string $value): InvoiceInterface;
    public function getSupplierCommunityVatNumber(): ?string;
    public function setSupplierName(string $value): InvoiceInterface;
    public function getSupplierName(): string;

    public function setSupplierAddress(Address $supplierAddress): InvoiceInterface;
    public function getSupplierAddress(): Address;
    public function setSupplierBankAccountNumber(?string $supplierBankAccountNumber): InvoiceInterface;
    public function getSupplierBankAccountNumber(): ?string;

    public const CUSTOMER_VAT_STATUS_DOMESTIC = 1;
    public const CUSTOMER_VAT_STATUS_OTHER = 2;
    public const CUSTOMER_VAT_STATUS_PRIVATE_PERSON = 3;

    public function getCustomerVatStatus(): int;
    public function getCustomerVatStatusString(): string;
    public function setCustomerVatStatus(int $customerVatStatus): InvoiceInterface;

    public function setCustomerTaxNumber(string $customerTaxNumber): InvoiceInterface;
    public function getCustomerTaxNumber(): string;
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
}