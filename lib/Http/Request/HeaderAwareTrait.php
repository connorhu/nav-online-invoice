<?php

namespace NAV\OnlineInvoice\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

trait HeaderAwareTrait
{
    /**
     * @Assert\NotBlank(groups={"v2.0", "v3.0"})
     */
    protected $header;
    
    public function setHeader(Header $value): HeaderAwareRequest
    {
        if ($this->header !== $value) {
            $this->header = $value;
            $value->setRequest($this);
        }
        
        return $this;
    }
    
    public function getHeader(): Header
    {
        return $this->header;
    }
    
}