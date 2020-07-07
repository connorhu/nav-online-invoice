<?php

namespace NAV\OnlineInvoice\Providers;

use NAV\OnlineInvoice\Http\Request;

interface CryptoToolsProviderInterface
{
    public function getUserPasswordHash(Request $request): string;
    
    public function signRequest(Request $request): string;
    
    public function encodeInvoiceData(string $invoiceData, Request $request = null): string;
    
    public function decodeExchangeToken(string $encodedToken): string;
}