<?php

namespace NAV\OnlineInvoice\Model;

use NAV\OnlineInvoice\Model\Enums\InvoiceOperationEnum;
use NAV\OnlineInvoice\Model\Interfaces\InvoiceInterface;
use Symfony\Component\Validator\Constraints as Assert;

class InvoiceOperation
{
    public function __construct(
        #[Assert\NotBlank]
        protected InvoiceOperationEnum $operation,
        #[Assert\Valid]
        protected InvoiceInterface $invoice
    ) {
    }

    /**
     * @return InvoiceOperationEnum
     */
    public function getOperation(): InvoiceOperationEnum
    {
        return $this->operation;
    }

    /**
     * @param InvoiceOperationEnum $operation
     * @return self
     */
    public function setOperation(InvoiceOperationEnum $operation): self
    {
        $this->operation = $operation;
        return $this;
    }

    /**
     * @return InvoiceInterface
     */
    public function getInvoice(): InvoiceInterface
    {
        return $this->invoice;
    }

    /**
     * @param InvoiceInterface $invoice
     * @return self
     */
    public function setInvoice(InvoiceInterface $invoice): self
    {
        $this->invoice = $invoice;
        return $this;
    }
}
