<?php

namespace NAV\OnlineInvoice\Http;

interface ExchangeTokenAwareRequest
{
    public function setExchangeToken(string $token);
    public function getExchangeToken(): string;
}