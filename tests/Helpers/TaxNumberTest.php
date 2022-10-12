<?php

namespace NAV\Tests\OnlineInvoice\Helpers;

use NAV\OnlineInvoice\Helpers\TaxNumber;
use PHPUnit\Framework\TestCase;

class TaxNumberTest extends TestCase
{
    public function testParse()
    {
        $this->assertEquals([
            'taxpayer_id' => '11111111',
            'vat_code' => '1',
            'country_code' => '11',
        ], TaxNumber::parse('111111111111'));

        $this->assertEquals([
            'taxpayer_id' => '11111111',
            'vat_code' => '1',
            'country_code' => '11',
        ], TaxNumber::parse('11111111111'));

        $this->assertEquals([
            'taxpayer_id' => '11111111',
            'vat_code' => '1',
            'country_code' => '1',
        ], TaxNumber::parse('1111111111'));

        $this->assertEquals([
            'taxpayer_id' => '11111111',
            'vat_code' => '1',
            'country_code' => null,
        ], TaxNumber::parse('111111111'));

        $this->assertEquals([
            'taxpayer_id' => '11111111',
            'vat_code' => null,
            'country_code' => null,
        ], TaxNumber::parse('11111111'));

        $this->assertEquals([
            'taxpayer_id' => '1111111',
            'vat_code' => null,
            'country_code' => null,
        ], TaxNumber::parse('1111111'));

        $this->assertEquals([
            'taxpayer_id' => null,
            'vat_code' => null,
            'country_code' => null,
        ], TaxNumber::parse(''));

        $this->assertEquals([
            'taxpayer_id' => '11111111',
        ], TaxNumber::parse('11111111111', TaxNumber::PART_TAXPAYER_ID));

        $this->assertEquals([
            'vat_code' => '1',
        ], TaxNumber::parse('11111111111', TaxNumber::PART_VAT_CODE));

        $this->assertEquals([
            'country_code' => '11',
        ], TaxNumber::parse('11111111111', TaxNumber::PART_COUNTY_CODE));

        $this->assertEquals([
            'taxpayer_id' => '11111111',
            'vat_code' => '1',
        ], TaxNumber::parse('11111111111', TaxNumber::PART_TAXPAYER_ID | TaxNumber::PART_VAT_CODE));
    }
}

