<?php

namespace NAV\OnlineInvoice\Entity\Interfaces;

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
    public function setSupplierAddress(AddressInterface $value): InvoiceInterface;
    public function getSupplierAddress(): AddressInterface;
    public function setSupplierBankAccountNumber(?string $value): InvoiceInterface;
    public function getSupplierBankAccountNumber(): ?string;

    public function setCustomerTaxNumber(string $value): InvoiceInterface;
    public function getCustomerTaxNumber(): string;
    public function setCustomerGroupMemberTaxNumber(?string $value): InvoiceInterface;
    public function getCustomerGroupMemberTaxNumber(): ?string;
    public function setCustomerCommunityVatNumber(?string $value): InvoiceInterface;
    public function getCustomerCommunityVatNumber(): ?string;
    public function setCustomerName(string $value): InvoiceInterface;
    public function getCustomerName(): string;
    public function setCustomerAddress(AddressInterface $value): InvoiceInterface;
    public function getCustomerAddress(): AddressInterface;
    public function setCustomerBankAccountNumber(?string $value): InvoiceInterface;
    public function getCustomerBankAccountNumber(): ?string;
}