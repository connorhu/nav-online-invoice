<?php

namespace NAV\OnlineInvoice\Model\Interfaces;

use NAV\OnlineInvoice\Model\Enums\ProductCodeCategoryEnum;

interface ProductCodeInterface
{
    public function getProductCodeCategory(): ?ProductCodeCategoryEnum;
    public function setProductCodeCategory(?ProductCodeCategoryEnum $productCodeCategory): ProductCodeInterface;

    public function getProductCodeValue(): ?string;
    public function setProductCodeValue(?string $productCodeValue): ProductCodeInterface;

    public function getProductCodeOwnValue(): ?string;
    public function setProductCodeOwnValue(?string $productCodeOwnValue): ProductCodeInterface;
}
