<?php

namespace NAV\OnlineInvoice\Providers;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\User;
use NAV\OnlineInvoice\Http\Request\Software;

use NAV\OnlineInvoice\Providers\ApiEndpointUrlProviderInterface;
use NAV\OnlineInvoice\Providers\UserProviderInterface;
use NAV\OnlineInvoice\Providers\RequestIdProviderInterface;
use NAV\OnlineInvoice\Providers\SoftwareProviderInterface;

class CompactDataProvider implements SoftwareProviderInterface, UserProviderInterface, RequestIdProviderInterface, ApiEndpointUrlProviderInterface
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
}