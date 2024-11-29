<?php

namespace NAV\OnlineInvoice\Model;

use NAV\OnlineInvoice\Model\Enums\ProductCodeCategoryEnum;
use NAV\OnlineInvoice\Model\Interfaces\ProductCodeInterface;

class ProductCode implements ProductCodeInterface
{
    /**
     * A termékkód értéke nem saját termékkód esetén
     *
     * requirements: required
     * node name: productCodeCategory
     * xml type: xs:string
     * simple type: ProductCodeCategoryType
     * pattern: 
     * enum: VTSZ SZJ KN AHK CSK KT EJ OWN OTHER

<line>
	<productCodes>
		<productCode>
			<productCodeCategory>VTSZ</productCodeCategory>
     */
    protected ?ProductCodeCategoryEnum $productCodeCategory = null;

    /**
     * A termékkód értéke
     *
     * requirements: required
     * node name: productCodeValue
     * xml type: xs:string
     * simple type: ProductCodeValueType
     * pattern: [A-Z0-9]{2,30}

<line>
	<productCodes>
		<productCode>
			<productCodeValue>02031110</productCodeValue>
     */
    protected ?string $productCodeValue = null;

    /**
     * Saját termékkód értéke
     *
     * requirements: required
     * node name: productCodeOwnValue
     * xml type: xs:string
     * simple type: SimpleText50NotBlankType
     * pattern: .*[^\s].*

    <line>
    <productCodes>
    <productCode>
    <productCodeOwnValue>xxxxx</productCodeOwnValue>
     */
    protected ?string $productCodeOwnValue = null;

    /**
     * @return ProductCodeCategoryEnum|null
     */
    public function getProductCodeCategory(): ?ProductCodeCategoryEnum
    {
        return $this->productCodeCategory;
    }

    /**
     * @param ProductCodeCategoryEnum|null $productCodeCategory
     * @return self
     */
    public function setProductCodeCategory(?ProductCodeCategoryEnum $productCodeCategory): ProductCodeInterface
    {
        $this->productCodeCategory = $productCodeCategory;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProductCodeValue(): ?string
    {
        return $this->productCodeValue;
    }

    /**
     * @param string|null $productCodeValue
     * @return self
     */
    public function setProductCodeValue(?string $productCodeValue): ProductCodeInterface
    {
        $this->productCodeValue = $productCodeValue;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProductCodeOwnValue(): ?string
    {
        return $this->productCodeOwnValue;
    }
    /**
     * @param string|null $productCodeOwnValue
     * @return self
     */
    public function setProductCodeOwnValue(?string $productCodeOwnValue): ProductCodeInterface
    {
        $this->productCodeOwnValue = $productCodeOwnValue;
        return $this;
    }
}
