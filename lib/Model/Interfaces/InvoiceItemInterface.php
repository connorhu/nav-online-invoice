<?php

namespace NAV\OnlineInvoice\Model\Interfaces;

use NAV\OnlineInvoice\Model\Enums\UnitOfMeasureEnum;

interface InvoiceItemInterface
{
    public function getUnitOfMeasure(): ?UnitOfMeasureEnum;
    public function setUnitOfMeasure(?UnitOfMeasureEnum $unitOfMeasure): InvoiceItemInterface;
}
