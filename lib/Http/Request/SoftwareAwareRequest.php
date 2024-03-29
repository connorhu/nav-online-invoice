<?php

namespace NAV\OnlineInvoice\Http\Request;


interface SoftwareAwareRequest
{
    public function setSoftware(Software $value): SoftwareAwareRequest;
    public function getSoftware(): Software;
}