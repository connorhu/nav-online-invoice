<?php

namespace NAV\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\ExhangeTokenAwareRequest;
use NAV\OnlineInvoice\Http\ExhangeTokenTrait;
use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Token\RequestToken;
use NAV\OnlineInvoice\Serialize\XMLSerialize;
use NAV\OnlineInvoice\Entity\InvoiceOperation;
use Symfony\Component\Validator\Constraints as Assert;

class ManageInvoiceRequest extends Request implements ExhangeTokenAwareRequest, SignableContentInterface
{
    use ExhangeTokenTrait;
    
    private $contentCompressed = false;
    
    /**
     * @Assert\NotBlank(groups="v2.0")
     * @Assert\Count(min=1, max=99, groups="v2.0")
     * @Assert\Valid()
     */
    private $invoiceOperations = [];
    
    public function __construct()
    {
        parent::__construct();
    }

    public function getRequiresExchangeToken(): bool
    {
        return true;
    }
    
    public function getRootNodeName()
    {
        return 'ManageInvoiceRequest';
    }

    public function getEndpointPath()
    {
        return '/manageInvoice';
    }
    
    public function isContentCompressed(): bool
    {
        return $this->contentCompressed;
    }
    
    /**
     * Add invoiceOperation
     *
     * @param AppBundle\Document\InvoiceOperation invoiceOperation
     */
    public function addInvoiceOperation(InvoiceOperation $invoiceOperation)
    {
        $this->invoiceOperations[] = $invoiceOperation;
        return $this;
    }

    /**
     * Remove invoiceOperation
     *
     * @param AppBundle\Document\InvoiceOperation invoiceOperation
     */
    public function removeInvoiceOperation(InvoiceOperation $invoiceOperation)
    {
        $this->invoiceOperations->removeElement($invoiceOperation);
        return $this;
    }

    /**
     * Getter for invoiceOperations
     *
     * @return mixed return value for Doctrine\Common\Collections\ArrayCollection|null
     */
    public function getInvoiceOperations()
    {
        return $this->invoiceOperations;
    }

    /**
     * setter for invoiceOperation
     *
     * @param mixed
     * @return self
     */
    public function setInvoiceOperations($value)
    {
        $this->invoiceOperations = $value;
        return $this;
    }

    public function normalizedContentToSignableContent($normalizedContent): iterable
    {
        $buffer = [];
        
        foreach ($normalizedContent['invoiceOperations']['invoiceOperation'] as $operation) {
            $buffer[] = $operation['invoiceOperation'].$operation['invoiceData'];
        }
        
        return $buffer;
    }

    // protected $technicalAnnulment = false;
    //
    // /**
    //  * setter for technicalAnnulment
    //  *
    //  * @param mixed
    //  * @return self
    //  */
    // public function setTechnicalAnnulment(bool $value)
    // {
    //     if ($this->technicalAnnulment === $value) {
    //         return;
    //     }
    //
    //     $this->technicalAnnulment = $value;
    //
    //     foreach ($this->invoiceOperations as $operation) {
    //         $this->validateOperationFlag($operation);
    //     }
    //
    //     return $this;
    // }
    //
    // /**
    //  * getter for technicalAnnulment
    //  *
    //  * @return mixed return value for
    //  */
    // public function getTechnicalAnnulment()
    // {
    //     return $this->technicalAnnulment;
    // }
    //

    //
    // private function validateOperationFlag(InvoiceOperation $operation)
    // {
    //     if ($this->technicalAnnulment === true) {
    //         if ($operation->getOperation() !== InvoiceOperation::OPERATION_ANNUL) {
    //             throw new \Exception('invalid operation when technicalAnnulment is true');
    //         }
    //     }
    //     else {
    //         if ($operation->getOperation() === InvoiceOperation::OPERATION_ANNUL) {
    //             throw new \Exception('invalid operation when technicalAnnulment is false');
    //         }
    //     }
    // }
    //
    // protected function getCrcChecksums()
    // {
    //     $buffer = [];
    //     $xmlSerialize = new XMLSerialize();
    //
    //     foreach ($this->invoiceOperations as $operation) {
    //         $xmlBody = $xmlSerialize->serialize($operation->getInvoice());
    //         $encodedBody = base64_encode($xmlBody);
    //
    //         $buffer[] = sprintf("%u", crc32($encodedBody));
    //     }
    //
    //     return $buffer;
    // }
    //
    // public function serialize()
    // {
    //     $serialized = parent::serialize();
    //     $serialized['exchangeToken'] = $this->token->token;
    //     $serialized['invoiceOperations'] = [
    //         'technicalAnnulment' => XMLSerialize::formatBoolean($this->technicalAnnulment),
    //         'compressedContent' => XMLSerialize::formatBoolean(false),
    //         'invoiceOperation' => [],
    //     ];
    //
    //     $xmlSerialize = new XMLSerialize();
    //     $i = 1;
    //     foreach ($this->invoiceOperations as $operation) {
    //         $xmlBody = $xmlSerialize->serialize($operation->getInvoice());
    //         $encodedBody = base64_encode($xmlBody);
    //
    //         $serialized['invoiceOperations']['invoiceOperation'][] = [
    //             'index' => $i++,
    //             'operation' => $operation->getOperation(),
    //             'invoice' => $encodedBody,
    //         ];
    //     }
    //
    //     return $serialized;
    // }
}