<?php

namespace NAV\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\ExchangeTokenAwareRequest;
use NAV\OnlineInvoice\Http\ExhangeTokenTrait;
use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\RequestServiceKindEnum;
use NAV\OnlineInvoice\Model\InvoiceOperation;
use Symfony\Component\Validator\Constraints as Assert;

class ManageInvoiceRequest extends Request implements ExchangeTokenAwareRequest, SignableContentInterface, HeaderAwareRequest, UserAwareRequest, SoftwareAwareRequest
{
    use ExhangeTokenTrait;
    use HeaderAwareTrait;
    use UserAwareTrait;
    use SoftwareAwareTrait;

    const ROOT_NODE_NAME = 'ManageInvoiceRequest';

    /**
     * @Assert\NotBlank(groups="v2.0")
     * @Assert\Count(min=1, max=99, groups="v2.0")
     * @Assert\Valid()
     */
    private array $invoiceOperations = [];

    public function getEndpointPath(): string
    {
        return '/manageInvoice';
    }

    public function getServiceKind(): RequestServiceKindEnum
    {
        return RequestServiceKindEnum::InvoiceService;
    }

    /**
     * @var bool
     */
    private bool $contentCompressed = false;

    public function isContentCompressed(): bool
    {
        return $this->contentCompressed;
    }

    /**
     * Add invoiceOperation
     *
     * @param InvoiceOperation $invoiceOperation
     * @return ManageInvoiceRequest
     */
    public function addInvoiceOperation(InvoiceOperation $invoiceOperation)
    {
        $this->invoiceOperations[] = $invoiceOperation;
        return $this;
    }

    /**
     * Remove invoiceOperation
     *
     * @param InvoiceOperation $invoiceOperation
     * @return ManageInvoiceRequest
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
    public function getInvoiceOperations(): array
    {
        return $this->invoiceOperations;
    }

    /**
     * setter for invoiceOperation
     *
     * @param mixed
     * @return self
     */
    public function setInvoiceOperations($value): ManageInvoiceRequest
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
}
