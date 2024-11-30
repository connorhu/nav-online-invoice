<?php

namespace NAV\OnlineInvoice\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

trait HeaderAwareTrait
{
    #[Assert\NotBlank(groups: ['v2.0', 'v3.0'])]
    protected ?Header $header = null;

    public function getHeader(): Header
    {
        return $this->header;
    }

    public function setHeader(Header $header): HeaderAwareRequest
    {
        if ($this->header !== $header) {
            $this->header = $header;
            $header->setRequest($this);
        }
        
        return $this;
    }
}
