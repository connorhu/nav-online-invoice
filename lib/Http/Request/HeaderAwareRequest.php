<?php

namespace NAV\OnlineInvoice\Http\Request;

use NAV\OnlineInvoice\Http\Request\Header;

interface HeaderAwareRequest
{
    public function setHeader(Header $value);
    public function getHeader();
}