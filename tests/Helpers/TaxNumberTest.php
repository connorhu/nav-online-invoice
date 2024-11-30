<?php

namespace NAV\OnlineInvoice\Tests\Helpers;

use NAV\OnlineInvoice\Helpers\TaxNumber;
use PHPUnit\Framework\TestCase;

class TaxNumberTest extends TestCase
{
    public function testParse()
    {
        $this->assertEquals([
            'taxpayer_id' => '11111111',
            'vat_code' => '1',
            'county_code' => '11',
        ], TaxNumber::parse('111111111111'));

        $this->assertEquals([
            'taxpayer_id' => '11111111',
            'vat_code' => '1',
            'county_code' => '11',
        ], TaxNumber::parse('11111111111'));

        $this->assertEquals([
            'taxpayer_id' => '11111111',
            'vat_code' => '1',
            'county_code' => '1',
        ], TaxNumber::parse('1111111111'));

        $this->assertEquals([
            'taxpayer_id' => '11111111',
            'vat_code' => '1',
            'county_code' => null,
        ], TaxNumber::parse('111111111'));

        $this->assertEquals([
            'taxpayer_id' => '11111111',
            'vat_code' => null,
            'county_code' => null,
        ], TaxNumber::parse('11111111'));

        $this->assertEquals([
            'taxpayer_id' => '1111111',
            'vat_code' => null,
            'county_code' => null,
        ], TaxNumber::parse('1111111'));

        $this->assertEquals([
            'taxpayer_id' => null,
            'vat_code' => null,
            'county_code' => null,
        ], TaxNumber::parse(''));

        $this->assertEquals([
            'taxpayer_id' => '11111111',
        ], TaxNumber::parse('11111111111', TaxNumber::PART_TAXPAYER_ID));

        $this->assertEquals([
            'vat_code' => '1',
        ], TaxNumber::parse('11111111111', TaxNumber::PART_VAT_CODE));

        $this->assertEquals([
            'county_code' => '11',
        ], TaxNumber::parse('11111111111', TaxNumber::PART_COUNTY_CODE));

        $this->assertEquals([
            'taxpayer_id' => '11111111',
            'vat_code' => '1',
        ], TaxNumber::parse('11111111111', TaxNumber::PART_TAXPAYER_ID | TaxNumber::PART_VAT_CODE));
    }
}

