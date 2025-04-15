<?php

namespace NAV\OnlineInvoice\Model;

use NAV\OnlineInvoice\Model\Enums\UnitOfMeasureEnum;
use NAV\OnlineInvoice\Model\Interfaces\InvoiceItemInterface;
use NAV\OnlineInvoice\Model\Interfaces\VatRateInterface;
use NAV\OnlineInvoice\Model\Traits\VatRateTrait;
use Symfony\Component\Validator\Constraints as Assert;

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
    protected ?int $lineModificationReferenceNumber = null;

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
    protected $lineModificationReferenceOperation = 'CREATE';

    // productCodes

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
    protected bool $advanceIndicator = false;

    /**
     * Az előlegszámla sorszáma, amelyben az előlegfizetés történt
     *
     * @var string|null
     */
    #[Assert\NotBlank(groups: ['advance_payment_data'])]
    protected ?string $advanceOriginalInvoice = null;

    /**
     * Az előleg fizetésének dátuma
     *
     * @var \DateTimeImmutable|null
     */
    #[Assert\NotBlank(groups: ['advance_payment_data'])]
    protected ?\DateTimeImmutable $advancePaymentDate = null;

    /**
     * Az előlegfizetéskor alkalmazott árfolyam
     *
     * @var string|null
     */
    #[Assert\NotBlank(groups: ['advance_payment_data'])]
    protected ?string/*Number*/ $advanceExchangeRate = null;

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
     * Értéke true, ha a tétel mennyiségi egysége természetes mértékegységben kifejezhető
     *
     * requirements: required
     * node name: lineExpressionIndicator
     * xml type: xs:boolean
     * simple type: boolean

<line>
	<lineExpressionIndicator>true</lineExpressionIndicator>
     */
    protected ?bool $lineExpressionIndicator = null;

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
    protected ?UnitOfMeasureEnum $unitOfMeasure = null;

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

    private $additionalItemData = [];

    /**
     * @return mixed
     */
    public function getItemNumber()
    {
        return $this->itemNumber;
    }

    /**
     * @param mixed $itemNumber
     */
    public function setItemNumber($itemNumber): void
    {
        $this->itemNumber = $itemNumber;
    }

    /**
     * @return int|null
     */
    public function getLineModificationReferenceNumber(): ?int
    {
        return $this->lineModificationReferenceNumber;
    }

    /**
     * @param int|null $lineModificationReferenceNumber
     * @return InvoiceItemInterface
     */
    public function setLineModificationReferenceNumber(?int $lineModificationReferenceNumber): InvoiceItemInterface
    {
        $this->lineModificationReferenceNumber = $lineModificationReferenceNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLineModificationReferenceOperation()
    {
        return $this->lineModificationReferenceOperation;
    }

    /**
     * @param mixed $lineModificationReferenceOperation
     */
    public function setLineModificationReferenceOperation($lineModificationReferenceOperation): InvoiceItemInterface
    {
        $this->lineModificationReferenceOperation = $lineModificationReferenceOperation;

        return $this;
    }

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

    /**
     * @return bool
     */
    public function getAdvanceIndicator(): bool
    {
        return $this->advanceIndicator;
    }

    /**
     * @return bool
     */
    public function isAdvanceIndicator(): bool
    {
        return $this->advanceIndicator;
    }

    /**
     * @param bool $advanceIndicator
     *
     * @return InvoiceItemInterface
     */
    public function setAdvanceIndicator(bool $advanceIndicator): static
    {
        $this->advanceIndicator = $advanceIndicator;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAdvanceOriginalInvoice(): ?string
    {
        return $this->advanceOriginalInvoice;
    }

    /**
     * @param string|null $advanceOriginalInvoice
     *
     * @return InvoiceItemInterface
     */
    public function setAdvanceOriginalInvoice(?string $advanceOriginalInvoice): static
    {
        $this->advanceOriginalInvoice = $advanceOriginalInvoice;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getAdvancePaymentDate(): ?\DateTimeImmutable
    {
        return $this->advancePaymentDate;
    }

    /**
     * @param \DateTimeImmutable|null $advancePaymentDate
     *
     * @return InvoiceItemInterface
     */
    public function setAdvancePaymentDate(?\DateTimeImmutable $advancePaymentDate): static
    {
        $this->advancePaymentDate = $advancePaymentDate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAdvanceExchangeRate(): ?string
    {
        return $this->advanceExchangeRate;
    }

    /**
     * @param string|null $advanceExchangeRate
     *
     * @return InvoiceItemInterface
     */
    public function setAdvanceExchangeRate(?string $advanceExchangeRate): static
    {
        $this->advanceExchangeRate = $advanceExchangeRate;

        return $this;
    }


    
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

    /**
     * @return bool|null
     */
    public function getLineExpressionIndicator(): ?bool
    {
        return $this->lineExpressionIndicator;
    }

    /**
     * @param bool|null $lineExpressionIndicator
     *
     * @return InvoiceItemInterface
     */
    public function setLineExpressionIndicator(bool $lineExpressionIndicator): static
    {
        $this->lineExpressionIndicator = $lineExpressionIndicator;

        return $this;
    }
    
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

    /**
     * @return UnitOfMeasureEnum|null
     */
    public function getUnitOfMeasure(): ?UnitOfMeasureEnum
    {
        return $this->unitOfMeasure;
    }

    /**
     * setter for unitOfMeasure
     *
     * @param UnitOfMeasureEnum|null $unitOfMeasure
     * @return self
     */
    public function setUnitOfMeasure(?UnitOfMeasureEnum $unitOfMeasure): InvoiceItemInterface
    {
        $this->unitOfMeasure = $unitOfMeasure;
        return $this;
    }
    
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
    
    public function addAdditionalItemData($key, $description, $value)
    {
        $this->additionalItemData[$key] = ['description' => $description, 'value' => $value];
    }
    
    public function getAdditionalItemData(): array
    {
        return $this->additionalItemData;
    }
}

