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
    
    const INVOCE_APPEARANCES = [
        'PAPER' => 1,
        'ELECTRONIC' => 2,
        'EDI' => 3,
        'UNKNOWN' => 4,
    ];
    
    const INVOCE_APPEARANCE_ENUMS = [
        1 => 'PAPER',
        2 => 'ELECTRONIC',
        3 => 'EDI',
        4 => 'UNKNOWN',
    ];
    
    const INVOCE_APPEARANCE_PAPER = 1;
    const INVOCE_APPEARANCE_ELECTRONIC = 2;
    const INVOCE_APPEARANCE_EDI = 3;
    const INVOCE_APPEARANCE_UNKNOWN = 4;
    
    public function setInvoiceAppearance(int $value): InvoiceInterface;
    public function getInvoiceAppearance(): ?int;
    
    public function addItem(InvoiceItemInterface $item): InvoiceInterface;
    public function removeItem(InvoiceItemInterface $item): InvoiceInterface;
    public function getItems(): iterable;
}