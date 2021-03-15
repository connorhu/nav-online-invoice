<?php

namespace NAV\OnlineInvoice\Providers;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\User;

class CryptoToolsProvider implements CryptoToolsProviderInterface
{
    public function getUserPasswordHash(User $user): string
    {
        return strtoupper(hash('sha512', $user->getPassword()));
    }
    
    public function getUserPasswordHashAlgo(User $user): string
    {
        return 'SHA-512';
    }
    
    public function signRequest(Request $request, iterable $content = null): string
    {
        $buffer = '';
        $buffer .= $request->getRequestId();
        $buffer .= $request->getHeader()->getTimestamp()->format('YmdHis');
        $buffer .= $request->getUser()->getSignKey();
        
        if ($content !== null) {
            foreach ($content as $item) {
                $buffer .= strtoupper(hash('sha3-512', $item));
            }
        }
        
        $requestVersion = $request->getRequestVersion();
        if ($requestVersion === Request::REQUEST_VERSION_V10 || $requestVersion === Request::REQUEST_VERSION_V11) {
            return strtoupper(hash('sha512', $buffer));
        }
        if ($requestVersion === Request::REQUEST_VERSION_V20) {
            return strtoupper(hash('sha3-512', $buffer));
        }
        if ($requestVersion === Request::REQUEST_VERSION_V30) {
            return strtoupper(hash('sha3-512', $buffer));
        }
    }
    
    public function getRequestSignatureHashAlgo(Request $request): string
    {
        return 'SHA3-512';
    }
    
    public function encodeInvoiceData(string $invoiceData, Request $request = null): string
    {
        return base64_encode($invoiceData);
    }
    
    public function decodeExchangeToken(string $encodedToken): string
    {
        return openssl_decrypt($encodedToken, 'AES-128-ECB', '2b503A5KHOG7W7Q9');
    }
}
