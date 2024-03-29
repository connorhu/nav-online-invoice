<?php

namespace NAV\OnlineInvoice\Http\Request;


interface HeaderAwareRequest
{
    public function setHeader(Header $value);
    public function getHeader();
}