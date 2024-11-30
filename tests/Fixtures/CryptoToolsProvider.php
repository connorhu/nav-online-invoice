<?php

namespace NAV\OnlineInvoice\Tests\Fixtures;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\User;
use NAV\OnlineInvoice\Providers\CryptoToolsProviderInterface;

class CryptoToolsProvider implements CryptoToolsProviderInterface
{
    public function getUserPasswordHash(User $user): string
    {
        return 'user-password-hash';
    }

    public function getUserPasswordHashAlgo(User $user): string
    {
        return 'SHA-512';
    }

    public function signRequest(Request $request): string
    {
        return 'request-signature';
    }

    public function getRequestSignatureHashAlgo(Request $request): string
    {
        return 'SHA3-512';
    }

    public function encodeInvoiceData(string $invoiceData, Request $request = null): string
    {
        return 'TODO';
    }

    public function decodeExchangeToken(string $encodedToken): string
    {
        return 'TODO';
    }
}
