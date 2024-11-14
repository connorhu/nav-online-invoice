<?php

namespace NAV\OnlineInvoice\Model;

class ProductCode
{
    const PRODUCT_CODE_CATEGORY_VTSZ = 'VTSZ';
    const PRODUCT_CODE_CATEGORY_SZJ = 'SZJ';
    const PRODUCT_CODE_CATEGORY_KN = 'KN';
    const PRODUCT_CODE_CATEGORY_AHK = 'AHK';
    const PRODUCT_CODE_CATEGORY_CSK = 'CSK';
    const PRODUCT_CODE_CATEGORY_KT = 'KT';
    const PRODUCT_CODE_CATEGORY_EJ = 'EJ';
    const PRODUCT_CODE_CATEGORY_OWN = 'OWN';
    const PRODUCT_CODE_CATEGORY_OTHER = 'OTHER';

    /*
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
    protected $productCodeCategory;
    
    /**
     * setter for productCodeCategory
     *
     * @param mixed 
     * @return self
     */
    public function setProductCodeCategory($value)
    {
        $this->productCodeCategory = $value;
        return $this;
    }
    
    /**
     * getter for productCodeCategory
     * 
     * @return mixed return value for 
     */
    public function getProductCodeCategory()
    {
        return $this->productCodeCategory;
    }
    
    /*
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
    protected $productCodeValue;
    
    /**
     * setter for productCodeValue
     *
     * @param mixed 
     * @return self
     */
    public function setProductCodeValue($value)
    {
        $this->productCodeValue = $value;
        return $this;
    }
    
    /**
     * getter for productCodeValue
     * 
     * @return mixed return value for 
     */
    public function getProductCodeValue()
    {
        return $this->productCodeValue;
    }
    
    /*
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
    protected $productCodeOwnValue;
    
    /**
     * setter for productCodeOwnValue
     *
     * @param mixed 
     * @return self
     */
    public function setProductCodeOwnValue($value)
    {
        $this->productCodeOwnValue = $value;
        return $this;
    }
    
    /**
     * getter for productCodeOwnValue
     * 
     * @return mixed return value for 
     */
    public function getProductCodeOwnValue()
    {
        return $this->productCodeOwnValue;
    }
}
