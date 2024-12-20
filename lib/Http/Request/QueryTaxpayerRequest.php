<?php

namespace NAV\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\RequestServiceKindEnum;
use Symfony\Component\Validator\Constraints as Assert;

class QueryTaxpayerRequest extends Request implements HeaderAwareRequest, UserAwareRequest, SoftwareAwareRequest
{
    use HeaderAwareTrait;
    use SoftwareAwareTrait;
    use UserAwareTrait;
    
    public const ROOT_NODE_NAME = 'QueryTaxpayerRequest';
    
    public function getEndpointPath(): string
    {
        return '/queryTaxpayer';
    }

    public function getServiceKind(): RequestServiceKindEnum
    {
        return RequestServiceKindEnum::InvoiceService;
    }

    /**
     * @Assert\NotBlank(groups={"v1.0", "v1.1", "v2.0", "v3.0"})
     * @Assert\Length(max=8, min=8, groups={"v1.0", "v1.1", "v2.0", "v3.0"})
     * @Assert\Regex("/^\d{8}$/", groups={"v1.0", "v1.1", "v2.0", "v3.0"})
     */
    protected $taxNumber;
    
    /**
     * setter for taxNumber
     *
     * @param mixed 
     * @return self
     */
    public function setTaxNumber(string $value): self
    {
        $this->taxNumber = substr($value, 0, 8);
        return $this;
    }
    
    /**
     * getter for taxNumber
     * 
     * @return mixed return value for 
     */
    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }
}
