<?php

namespace NAV\OnlineInvoice\Model\Interfaces;

use NAV\OnlineInvoice\Model\Enums\UnitOfMeasureEnum;

interface InvoiceItemInterface
{
    public function getUnitOfMeasure(): ?UnitOfMeasureEnum;
    public function setUnitOfMeasure(?UnitOfMeasureEnum $unitOfMeasure): InvoiceItemInterface;

    public function getUnitPriceHuf();
    public function setUnitPriceHuf($unitPriceHuf): void;

    /** discount */
    public function getDiscountDescription(): ?string;
    public function setDiscountDescription(?string $discountDescription): void;
    public function getDiscountValue(): ?float;
    public function setDiscountValue(?float $discountValue): void;
    public function getDiscountRate(): ?float;
    public function setDiscountRate(?float $discountRate): void;
}
