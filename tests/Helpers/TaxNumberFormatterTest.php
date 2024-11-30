<?php

namespace NAV\OnlineInvoice\Tests\Helpers;

use NAV\OnlineInvoice\Helpers\TaxNumberFormatter;
use PHPUnit\Framework\TestCase;

class TaxNumberFormatterTest extends TestCase
{
    /**
     * @dataProvider formatHUDataProvider
     */
    public function testFormatHU($taxNumber, $expectedTaxNumber)
    {
        $this->assertSame($expectedTaxNumber, TaxNumberFormatter::formatHU($taxNumber));
    }

    public static function formatHUDataProvider(): \Generator
    {
        yield ['1234', '1234'];
        yield ['12345678', '12345678'];
        yield ['123456789', '12345678-9'];
        yield ['1234567891', '12345678-9-1'];
        yield ['12345678912', '12345678-9-12'];
        yield ['12345678912345', '12345678-9-12345'];
    }
}
