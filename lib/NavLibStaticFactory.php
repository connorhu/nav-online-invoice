<?php

namespace Nav;

use Psr\Log\LoggerInterface;

class NavLibStaticFactory
{
    private static function getSettings($requestStack, $companyParameters)
    {
        $request = $requestStack->getMasterRequest();
        
        if ($request->attributes->has('baseTaxId')) {
            $taxId = (int) $request->attributes->get('baseTaxId');
        }
        else {
            $taxId = key($companies);
        }
        
        if (!isset($companyParameters[$taxId])) {
            throw new \Exception('base tax id not found');
        }
        
        return $companyParameters[$taxId];
    }
    
    public static function createNavRestClient($requestStack, $companyParameters, LoggerInterface $logger = null)
    {
        $settings = self::getSettings($requestStack, $companyParameters);
        
        return new NavRestClient($settings['test'], $settings['login'], $settings['password'], $settings['signKey'], $settings['softwareInfo'], $settings['baseTaxnumber'], $settings['requestPrefix'], $logger);
    }

    public static function createEncoder($requestStack, $companyParameters)
    {
        $settings = self::getSettings($requestStack, $companyParameters);
        
        return new Encoder($settings['xmlKey']);
    }
}
