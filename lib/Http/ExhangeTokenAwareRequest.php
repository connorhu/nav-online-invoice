<?php

namespace NAV\OnlineInvoice\Http;

interface ExhangeTokenAwareRequest
{
    public function setExchangeToken(string $token);
    public function getExchangeToken(): string;
}