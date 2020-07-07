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
}