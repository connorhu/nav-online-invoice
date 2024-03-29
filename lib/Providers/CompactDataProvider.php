<?php

namespace NAV\OnlineInvoice\Providers;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\User;
use NAV\OnlineInvoice\Http\Request\Software;


class CompactDataProvider implements SoftwareProviderInterface, UserProviderInterface, RequestIdProviderInterface, ApiEndpointUrlProviderInterface, CryptoToolsProviderInterface
{
    private $infoJson;
    private $software;
    private $user;
    
    public function __construct(string $infoJsonFilePath)
    {
        $this->infoJson = json_decode(file_get_contents($infoJsonFilePath), true);
        
        $softwareKeys = [
            'id',
            'name',
            'operation',
            'mainVersion',
            'devName',
            'devContact',
            'devCountryCode',
            'devTaxNumber',
        ];
        $this->software = new Software();
        foreach ($softwareKeys as $key) {
            $this->software->{'set'.ucfirst($key)}($this->infoJson['software'][$key]);
        }
        
        $userKeys = [
            'login',
            'password',
            'taxNumber',
            'signKey',
        ];
        $this->user = new User();
        foreach ($userKeys as $key) {
            $this->user->{'set'.ucfirst($key)}($this->infoJson['user'][$key]);
        }
    }
    
    public function getSoftware(): Software
    {
        return $this->software;
    }
    
    public function getRequestId(): string
    {
        return $this->infoJson['requestPrefix'] . str_replace('.', '', microtime(true));;
    }
    
    public function getUser(): User
    {
        return $this->user;
    }
    
    public function getEndpointUrl(Request $request): string
    {
        $host = $this->infoJson['test'] ? 'api-test.onlineszamla.nav.gov.hu' : 'api.onlineszamla.nav.gov.hu';
        
        $version = '/v3';
        
        return 'https://'. $host .'/'. $request::SERVICE_NAME . $version . $request->getEndpointPath();
    }
    
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
        if ($requestVersion === Request::REQUEST_VERSION_V20 || $requestVersion === Request::REQUEST_VERSION_V30) {
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
        return openssl_decrypt($encodedToken, 'AES-128-ECB', $this->infoJson['user']['xmlExchangeKey']);
    }
}