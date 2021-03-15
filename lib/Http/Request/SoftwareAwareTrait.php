<?php

namespace NAV\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\Request\Software;

trait SoftwareAwareTrait
{
    protected $software;
    
    public function setSoftware(Software $value): SoftwareAwareRequest
    {
        $this->software = $software;
        
        return $this;
    }
    
    public function getSoftware(): Software
    {
        return $this->software;
    }
}