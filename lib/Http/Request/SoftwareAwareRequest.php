<?php

namespace NAV\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\Request\Software;

interface SoftwareAwareRequest
{
    public function setSoftware(Software $value): SoftwareAwareRequest;
    public function getSoftware(): Software;
}