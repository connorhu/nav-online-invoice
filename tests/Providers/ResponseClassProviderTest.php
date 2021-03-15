<?php

namespace NAV\Tests\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Response\QueryTaxpayerResponse;
use NAV\OnlineInvoice\Providers\ResponseClassProvider;
use NAV\OnlineInvoice\Serializer\Normalizers\HeaderNormalizer;
use PHPUnit\Framework\TestCase;

class ResponseClassProviderTest extends TestCase
{
    /**
     * @dataProvider dataSet
     */
    public function testResponseClass($content, $classProvided)
    {
        $provider = new ResponseClassProvider();
        $this->assertSame($provider->getResponseClass($content), $classProvided);
    }
    
    public function dataSet()
    {
        return [
            ['<?xml version="1.0" encoding="UTF-8" standalone="yes"?><ns2:QueryTaxpayerResponse xmlns="http://schemas.nav.gov.hu/NTCA/1.0/common" xmlns:ns2="http://schemas.nav.gov.hu/OSA/3.0/api" xmlns:ns3="http://schemas.nav.gov.hu/OSA/3.0/base" xmlns:ns4="http://schemas.nav.gov.hu/OSA/3.0/data">...', QueryTaxpayerResponse::class],
            ['<?xml version="1.0" encoding="UTF-8"?><QueryTaxpayerResponse ...>...', QueryTaxpayerResponse::class]
        ];
    }
}
