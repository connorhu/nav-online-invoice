<?php

namespace NAV\OnlineInvoice\Model\Interfaces;

use NAV\OnlineInvoice\Model\Enums\UnitOfMeasureEnum;

interface InvoiceItemInterface
{
    public function getAdvanceIndicator(): bool;
    public function isAdvanceIndicator(): bool;
    public function setAdvanceIndicator(bool $advanceIndicator): static;

    public function getAdvanceOriginalInvoice(): ?string;
    public function setAdvanceOriginalInvoice(?string $advanceOriginalInvoice): static;

    public function getAdvancePaymentDate(): ?\DateTimeImmutable;
    public function setAdvancePaymentDate(?\DateTimeImmutable $advancePaymentDate): static;

    public function getAdvanceExchangeRate(): ?string;
    public function setAdvanceExchangeRate(?string $advanceExchangeRate): static;

    public function getUnitOfMeasure(): ?UnitOfMeasureEnum;
    public function setUnitOfMeasure(?UnitOfMeasureEnum $unitOfMeasure): InvoiceItemInterface;
}
