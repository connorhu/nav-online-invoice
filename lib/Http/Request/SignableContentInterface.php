<?php

namespace NAV\OnlineInvoice\Http\Request;

interface SignableContentInterface
{
    public function normalizedContentToSignableContent($normalizedContent): iterable;
}