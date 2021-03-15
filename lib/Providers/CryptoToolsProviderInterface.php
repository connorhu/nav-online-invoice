<?php

namespace NAV\OnlineInvoice\Providers;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\User;

interface CryptoToolsProviderInterface
{
    public function getUserPasswordHash(User $user): string;

    public function getUserPasswordHashAlgo(User $user): string;
    
    public function signRequest(Request $request): string;
    
    public function getRequestSignatureHashAlgo(Request $request): string;
    
    public function encodeInvoiceData(string $invoiceData, Request $request = null): string;
    
    public function decodeExchangeToken(string $encodedToken): string;
}