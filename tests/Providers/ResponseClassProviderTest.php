<?php

namespace NAV\OnlineInvoice\Tests\Providers;

use NAV\OnlineInvoice\Http\Response\QueryTaxpayerResponse;
use NAV\OnlineInvoice\Http\Response\TokenExchangeResponse;
use NAV\OnlineInvoice\Providers\ResponseClassProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ResponseClassProviderTest extends TestCase
{
    #[DataProvider('dataSetDataProvider')]
    public function testResponseClass($content, $classProvided)
    {
        $this->assertSame(ResponseClassProvider::getResponseClass($content), $classProvided);
    }
    
    public static function dataSetDataProvider()
    {
        return [
            ['<?xml version="1.0" encoding="UTF-8" standalone="yes"?><ns2:QueryTaxpayerResponse xmlns="http:...', QueryTaxpayerResponse::class],
            ['<?xml version="1.0" encoding="UTF-8"?><QueryTaxpayerResponse ...>...', QueryTaxpayerResponse::class],
            ['<?xml version="1.0" encoding="UTF-8" standalone="yes"?><TokenExchangeResponse xmlns="http://schemas.nav.gov.hu/OSA/3.0/api" ...', TokenExchangeResponse::class],
        ];
    }
}
