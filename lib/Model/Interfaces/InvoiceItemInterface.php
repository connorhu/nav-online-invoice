<?php

namespace NAV\OnlineInvoice\Model\Interfaces;

use NAV\OnlineInvoice\Model\Enums\UnitOfMeasureEnum;

interface InvoiceItemInterface
{
    public function getAdvanceIndicator(): bool;
    public function isAdvanceIndicator(): bool;
    public function setAdvanceIndicator(bool $advanceIndicator): static;

    public function getUnitOfMeasure(): ?UnitOfMeasureEnum;
    public function setUnitOfMeasure(?UnitOfMeasureEnum $unitOfMeasure): InvoiceItemInterface;
}
