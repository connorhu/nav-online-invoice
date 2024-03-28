<?php

namespace NAV\OnlineInvoice\Model;

use NAV\OnlineInvoice\Model\Interfaces\InvoiceItemInterface;
use NAV\OnlineInvoice\Model\Interfaces\VatRateInterface;
use NAV\OnlineInvoice\Model\Traits\VatRateTrait;

class InvoiceItem implements InvoiceItemInterface, VatRateInterface
{
    use VatRateTrait;
    
    /*
     * A tétel sorszáma
     *
     * requirements: required
     * node name: lineNumber
     * xml type: xs:nonNegativeInteger
     * simple type: LineNumberType
     * pattern: 

     */
    protected $itemNumber;
    
    /**
     * setter for itemNumber
     *
     * @param mixed 
     * @return self
     */
    public function setItemNumber($value)
    {
        $this->itemNumber = $value;
        return $this;
    }
    
    /**
     * getter for itemNumber
     * 
     * @return mixed return value for 
     */
    public function getItemNumber()
    {
        return $this->itemNumber;
    }

    // productCodes

    /*
     * Módosító számla esetén a tételsorszintű módosítások jelölése
     * Az eredeti számla módosítással érintett tételének sorszáma, (lineNumber). Új tétel létrehozása esetén az új tétel sorszáma, az eredeti számla folytatásaként.
     *
     * requirements: not required / required
     * node name: lineModificationReference / lineNumberReference
     * xml type: LineModificationReferenceType / xs:nonNegativeInteger
     * simple type: LineModificationReferenceType
     * pattern: 

     */
    protected $lineModificationReferenceNumber;
    
    /**
     * setter for lineModificationReferenceNumber
     *
     * @param mixed 
     * @return self
     */
    public function setLineModificationReferenceNumber($value)
    {
        $this->lineModificationReferenceNumber = $value;
        return $this;
    }
    
    /**
     * getter for lineModificationReferenceNumber
     * 
     * @return mixed return value for 
     */
    public function getLineModificationReferenceNumber()
    {
        return $this->lineModificationReferenceNumber;
    }
    
    /*
     * A számlatétel módosításának jellege.
     *
     * requirements: not required / required
     * node name: lineModificationReference / lineNumberReference
     * xml type: LineModificationReferenceType
     * simple type: LineModificationReferenceType
     * pattern: 
     * enum: CREATE MODIFY

     */
    protected $lineModificationReferenceOperation;
    
    /**
     * setter for lineModificationReferenceOperation
     *
     * @param mixed 
     * @return self
     */
    public function setLineModificationReferenceOperation($value)
    {
        $this->lineModificationReferenceOperation = $value;
        return $this;
    }
    
    /**
     * getter for lineModificationReferenceOperation
     * 
     * @return mixed return value for 
     */
    public function getLineModificationReferenceOperation()
    {
        return $this->lineModificationReferenceOperation;
    }
    
    /*
     * Hivatkozások kapcsolódó tételekre, ha ez az ÁFA törvény alapján szükséges
     *
     * requirements: not required
     * node name: referencesToOtherLines / referenceToOtherLine[]
     * xml type: ReferencesToOtherLinesType / xs:nonNegativeInteger[]
     * simple type: ReferencesToOtherLinesType / LineNumberType
     * pattern: 
     */
    protected $referencesToOtherLines;
    
    /**
     * setter for referencesToOtherLines
     *
     * @param mixed 
     * @return self
     */
    public function setReferencesToOtherLines($value)
    {
        $this->referencesToOtherLines = $value;
        return $this;
    }
    
    /**
     * getter for referencesToOtherLines
     * 
     * @return mixed return value for 
     */
    public function getReferencesToOtherLines()
    {
        return $this->referencesToOtherLines;
    }
    
    /*
     * Értéke true, ha a számla tétel előleg jellegű.
     *
     * requirements: not required
     * node name: advanceIndicator
     * xml type: xs:boolean
     * simple type: boolean
     * pattern: 
     * default: false

     */
    protected $advanceIndicator = false;
    
    /**
     * setter for advanceIndicator
     *
     * @param mixed 
     * @return self
     */
    public function setAdvanceIndicator($value)
    {
        $this->advanceIndicator = $value;
        return $this;
    }
    
    /**
     * getter for advanceIndicator
     * 
     * @return mixed return value for 
     */
    public function getAdvanceIndicator()
    {
        return $this->advanceIndicator;
    }
    
    /*
     * Termékkódok
     *
     * requirements: required
     * node name: productCodes
     * xml type: ProductCodesType
     * simple type: ProductCodesType
     * pattern: 

<line>
	<productCodes>
     */
    protected $productCodes = [];
    
    /**
     * Add productCode
     *
     * @param AppBundle\Document\productCode productCode
     */
    public function addProductCode(ProductCode $productCode)
    {
        $this->productCodes[] = $productCode;
        return $this;
    }

    public function addProductCodeWithDetails($category, $value, $ownValue = null)
    {
        $code = new ProductCode();
        $code->setProductCodeCategory($category);
        $code->setProductCodeValue($value);
        $code->setProductCodeOwnValue($ownValue);
        
        $this->productCodes[] = $code;
        return $this;
    }
    
    /**
     * Remove productCode
     *
     * @param AppBundle\Document\ProductCode productCode
     */
    public function removeProductCode(ProductCode $productCode)
    {
        $this->productCodes->removeElement($productCode);
        return $this;
    }
    
    /**
     * Getter for productCodes
     * 
     * @return mixed return value for Doctrine\Common\Collections\ArrayCollection|null
     */
    public function getProductCodes()
    {
        return $this->productCodes;
    }

    /*
     * Értéke true, ha a tétel mennyiségi egysége természetes mértékegységben kifejezhető
     *
     * requirements: required
     * node name: lineExpressionIndicator
     * xml type: xs:boolean
     * simple type: boolean

<line>
	<lineExpressionIndicator>true</lineExpressionIndicator>
     */
    protected $lineExpressionIndicator;
    
    /**
     * setter for lineExpressionIndicator
     *
     * @param mixed 
     * @return self
     */
    public function setLineExpressionIndicator($value)
    {
        $this->lineExpressionIndicator = $value;
        return $this;
    }
    
    /**
     * getter for lineExpressionIndicator
     * 
     * @return mixed return value for 
     */
    public function getLineExpressionIndicator()
    {
        return $this->lineExpressionIndicator;
    }

    /*
     * A termék vagy szolgáltatás megnevezése
     *
     * requirements: not required
     * node name: lineDescription
     * xml type: xs:string
     * simple type: SimpleText255NotBlankType
     * pattern: .*[^\s].*

<line>
	<lineDescription>Hűtött házi sertés (fél)</lineDescription>
     */
    protected $lineDescription;
    
    /**
     * setter for lineDescription
     *
     * @param mixed 
     * @return self
     */
    public function setLineDescription($value)
    {
        $this->lineDescription = $value;
        return $this;
    }
    
    /**
     * getter for lineDescription
     * 
     * @return mixed return value for 
     */
    public function getLineDescription()
    {
        return $this->lineDescription;
    }
    
    /*
     * Mennyiség
     *
     * requirements: not required
     * node name: quantity
     * xml type: xs:decimal
     * simple type: QuantityType
     * pattern: 

<line>
	<quantity>1500.00</quantity>
     */
    protected $quantity;
    
    /**
     * setter for quantity
     *
     * @param mixed 
     * @return self
     */
    public function setQuantity($value)
    {
        $this->quantity = $value;
        return $this;
    }
    
    /**
     * getter for quantity
     * 
     * @return mixed return value for 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
    
    /*
     * Mennyiségi egység
     *
     * requirements: not required
     * node name: unitOfMeasure
     * xml type: xs:string
     * simple type: SimpleText50NotBlankType
     * pattern: .*[^\s].*

<line>
	<unitOfMeasure>kg</unitOfMeasure>
     */
    protected $unitOfMeasure;
    
    /**
     * setter for unitOfMeasure
     *
     * @param mixed 
     * @return self
     */
    public function setUnitOfMeasure($value)
    {
        $this->unitOfMeasure = $value;
        return $this;
    }
    
    /**
     * getter for unitOfMeasure
     * 
     * @return mixed return value for 
     */
    public function getUnitOfMeasure()
    {
        return $this->unitOfMeasure;
    }
    
    /*
     * Mennyiségi egység
     *
     * requirements: not required
     * node name: unitOfMeasureOwn
     * xml type: xs:string
     * simple type: UnitOfMeasureType
     * pattern: -
     * enum:
        - PIECE
        - KILOGRAM
        - TON
        - KWH
        - DAY
        - HOUR
        - MINUTE
        - MONTH
        - LITER
        - KILOMETER
        - CUBIC_METER
        - METER
        - LINEAR_METER
        - CARTON
        - PACK
        - OWN 

<line>
	<unitOfMeasureOwn>kg</unitOfMeasureOwn>
     */
    protected $unitOfMeasureOwn;
    
    /**
     * setter for unitOfMeasureOwn
     *
     * @param mixed 
     * @return self
     */
    public function setUnitOfMeasureOwn($value)
    {
        $this->unitOfMeasureOwn = $value;
        return $this;
    }
    
    /**
     * getter for unitOfMeasureOwn
     * 
     * @return mixed return value for 
     */
    public function getUnitOfMeasureOwn()
    {
        return $this->unitOfMeasureOwn;
    }
    
    /*
     * Egységár a számla pénznemében. Egyszerűsített számla esetén bruttó, egyéb esetben nettó egységár.
     *
     * requirements: not required
     * node name: unitPrice
     * xml type: xs:decimal
     * simple type: QuantityType
     * pattern: 

<line>
	<unitPrice>400.00</unitPrice>
     */
    protected $unitPrice;
    
    /**
     * setter for unitPrice
     *
     * @param mixed 
     * @return self
     */
    public function setUnitPrice($value)
    {
        $this->unitPrice = $value;
        return $this;
    }
    
    /**
     * getter for unitPrice
     * 
     * @return mixed return value for 
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }
    
    /*
     * Tétel nettó összege a számla pénznemében.
     *
     * requirements: required
     * node name: lineNetAmount
     * xml type: xs:decimal 
     * simple type: MonetaryType
     * pattern: 

<line>
	<lineAmountsNormal>
		<lineNetAmount>600000.00</lineNetAmount>
     */
    protected $netAmount;
    
    /**
     * setter for itemNetAmount
     *
     * @param mixed 
     * @return self
     */
    public function setNetAmount($value)
    {
        $this->netAmount = $value;
        return $this;
    }
    
    /**
     * getter for itemNetAmount
     * 
     * @return mixed return value for 
     */
    public function getNetAmount()
    {
        return $this->netAmount;
    }
    
    
    /*
     * Tétel nettó összege a számla pénznemében.
     *
     * requirements: required
     * node name: lineNetAmountHUF
     * xml type: xs:decimal 
     * simple type: MonetaryType
     * pattern: 

<line>
	<lineAmountsNormal>
        <lineNetAmountData>
            <lineNetAmountHUF>600000.00</lineNetAmountHUF>
     */
    protected $netAmountHUF;
    
    /**
     * setter for itemNetAmount
     *
     * @param mixed 
     * @return self
     */
    public function setNetAmountHUF($value)
    {
        $this->netAmountHUF = $value;
        return $this;
    }
    
    /**
     * getter for itemNetAmount
     * 
     * @return mixed return value for 
     */
    public function getNetAmountHUF()
    {
        return $this->netAmountHUF;
    }
    
    /*
     * Tétel ÁFA összege a számla pénznemében
     *
     * requirements: required
     * node name: lineVatAmount
     * xml type: xs:decimal
     * simple type: MonetaryType
     * pattern: 

     */
    protected $vatAmount;
    
    /**
     * setter for vatAmount
     *
     * @param mixed 
     * @return self
     */
    public function setVatAmount($value)
    {
        $this->vatAmount = $value;
        return $this;
    }
    
    /**
     * getter for vatAmount
     * 
     * @return mixed return value for 
     */
    public function getVatAmount()
    {
        return $this->vatAmount;
    }
    
    /*
     * Tétel ÁFA összege forintban
     *
     * requirements: not required
     * node name: lineVatAmountHUF
     * xml type: xs:decimal
     * simple type: MonetaryType
     * pattern: 

     */
    protected $vatAmountHUF;
    
    /**
     * setter for vatAmountHUF
     *
     * @param mixed 
     * @return self
     */
    public function setVatAmountHUF($value)
    {
        $this->vatAmountHUF = $value;
        return $this;
    }
    
    /**
     * getter for vatAmountHUF
     * 
     * @return mixed return value for 
     */
    public function getVatAmountHUF()
    {
        return $this->vatAmountHUF;
    }
    
    /*
     * Tétel bruttó értéke a számla pénznemében 
     *
     * requirements: not required
     * node name: lineGrossAmountNormal
     * xml type: xs:decimal
     * simple type: MonetaryType
     * pattern: 

     */
    protected $grossAmountNormal;
    
    /**
     * setter for grossAmountNormal
     *
     * @param mixed 
     * @return self
     */
    public function setGrossAmountNormal($value)
    {
        $this->grossAmountNormal = $value;
        return $this;
    }
    
    /**
     * getter for grossAmountNormal
     * 
     * @return mixed return value for 
     */
    public function getGrossAmountNormal()
    {
        return $this->grossAmountNormal;
    }
    
    /*
     * Tétel bruttó értéke a számla pénznemében 
     *
     * requirements: required
     * node name: lineGrossAmountNormalHUF
     * xml type: xs:decimal
     * simple type: MonetaryType
     * pattern: 

     */
    protected $grossAmountNormalHUF;
    
    /**
     * setter for grossAmountNormal
     *
     * @param mixed 
     * @return self
     */
    public function setGrossAmountNormalHUF($value)
    {
        $this->grossAmountNormalHUF = $value;
        return $this;
    }
    
    /**
     * getter for grossAmountNormal
     * 
     * @return mixed return value for 
     */
    public function getGrossAmountNormalHUF()
    {
        return $this->grossAmountNormalHUF;
    }
    
    /*
     * 
     *
     * requirements: required
     * node name: 
     * xml type: 
     * simple type: 
     * pattern: 

     */
    protected $intermediatedService;
    
    /**
     * setter for intermediatedService
     *
     * @param mixed 
     * @return self
     */
    public function setIntermediatedService($value)
    {
        $this->intermediatedService = $value;
        return $this;
    }
    
    /**
     * getter for intermediatedService
     * 
     * @return mixed return value for 
     */
    public function getIntermediatedService()
    {
        return $this->intermediatedService;
    }
    
    private $additionalData = [];
    
    public function addAdditionalData($key, $description, $value)
    {
        $this->additionalData[$key] = ['description' => $description, 'value' => $value];
    }
    
    public function getAdditionalData(): array
    {
        return $this->additionalData;
    }
}

class_alias(InvoiceItem::class, \NAV\OnlineInvoice\Model\InvoiceItem::class);

