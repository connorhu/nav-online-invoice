<?php

namespace NAV\OnlineInvoice\Entity\Interfaces;

interface InvoiceItemInterface extends VatRateInterface
{
    public function setItemNumber($value);
    public function getItemNumber();
    public function setLineModificationReferenceNumber($value);
    public function getLineModificationReferenceNumber();
    public function setLineModificationReferenceOperation($value);
    public function getLineModificationReferenceOperation();
    public function setReferencesToOtherLines($value);
    public function getReferencesToOtherLines();
    public function setAdvanceIndicator($value);
    public function getAdvanceIndicator();
    public function addProductCode(ProductCode $productCode);
    public function addProductCodeWithDetails($category, $value, $ownValue = null);
    public function removeProductCode(ProductCode $productCode);
    public function getProductCodes(): iterable;
    public function setLineExpressionIndicator($value);
    public function getLineExpressionIndicator();
    public function setLineDescription($value);
    public function getLineDescription();
    public function setQuantity($value);
    public function getQuantity();
    public function setUnitOfMeasure($value);
    public function getUnitOfMeasure();
    public function setUnitOfMeasureOwn($value);
    public function getUnitOfMeasureOwn();
    public function setUnitPrice($value);
    public function getUnitPrice();
    public function setNetAmount($value);
    public function getNetAmount();
    public function setNetAmountHUF($value);
    public function getNetAmountHUF();
    public function setVatAmount($value);
    public function getVatAmount();
    public function setVatAmountHUF($value);
    public function getVatAmountHUF();
    public function setGrossAmountNormal($value);
    public function getGrossAmountNormal();
    public function setGrossAmountNormalHUF($value);
    public function getGrossAmountNormalHUF();
    public function setIntermediatedService($value);
    public function getIntermediatedService();
    public function addAdditionalData($key, $description, $value);
    public function getAdditionalData(): iterable;
}

