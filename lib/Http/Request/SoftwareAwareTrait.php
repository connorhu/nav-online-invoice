<?php

namespace NAV\OnlineInvoice\Http\Request;


trait SoftwareAwareTrait
{
    protected $software;
    
    public function setSoftware(Software $value): SoftwareAwareRequest
    {
        $this->software = $value;
        
        return $this;
    }
    
    public function getSoftware(): Software
    {
        return $this->software;
    }
}