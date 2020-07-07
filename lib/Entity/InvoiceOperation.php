<?php

namespace NAV\OnlineInvoice\Entity;

use NAV\OnlineInvoice\Entity\Interfaces\InvoiceInterface;
use Symfony\Component\Validator\Constraints as Assert;

class InvoiceOperation
{
    const OPERATION_CREATE = 'CREATE';
    const OPERATION_MODIFY = 'MODIFY';
    const OPERATION_STORNO = 'STORNO';
    const OPERATION_ANNUL = 'ANNUL'; // deprecated and separated
    
    public function __construct($operation, InvoiceInterface $invoice)
    {
        $this->operation = $operation;
        $this->invoice = $invoice;
    }
    
    /**
     * @Assert\NotBlank()
     */
    protected $operation;
    
    /**
     * setter for operation
     *
     * @param mixed 
     * @return self
     */
    public function setOperation(string $value): self
    {
        $this->operation = $value;
        return $this;
    }
    
    /**
     * getter for operation
     * 
     * @return mixed return value for 
     */
    public function getOperation(): string
    {
        return $this->operation;
    }
    
    /**
     * @Assert\Valid()
     */
    protected $invoice;
    
    /**
     * setter for invoice
     *
     * @param mixed 
     * @return self
     */
    public function setInvoice(InvoiceInterface $value): self
    {
        $this->invoice = $value;
        return $this;
    }
    
    /**
     * getter for invoice
     * 
     * @return mixed return value for 
     */
    public function getInvoice(): InvoiceInterface
    {
        return $this->invoice;
    }
}
