<?php

namespace NAV\Tests\OnlineInvoice\Helpers;

use NAV\OnlineInvoice\Helpers\TaxNumber;
use PHPUnit\Framework\TestCase;

class TaxNumberTest extends TestCase
{
    public function testParse()
    {
        $this->assertEquals(TaxNumber::parse('111111111111'), [
            'taxpayerid' => '11111111',
            'vatcode' => '1',
            'countrycode' => '11',
        ]);

        $this->assertEquals(TaxNumber::parse('11111111111'), [
            'taxpayerid' => '11111111',
            'vatcode' => '1',
            'countrycode' => '11',
        ]);

        $this->assertEquals(TaxNumber::parse('1111111111'), [
            'taxpayerid' => '11111111',
            'vatcode' => '1',
            'countrycode' => '1',
        ]);

        $this->assertEquals(TaxNumber::parse('111111111'), [
            'taxpayerid' => '11111111',
            'vatcode' => '1',
            'countrycode' => null,
        ]);

        $this->assertEquals(TaxNumber::parse('11111111'), [
            'taxpayerid' => '11111111',
            'vatcode' => null,
            'countrycode' => null,
        ]);

        $this->assertEquals(TaxNumber::parse('1111111'), [
            'taxpayerid' => '1111111',
            'vatcode' => null,
            'countrycode' => null,
        ]);

        $this->assertEquals(TaxNumber::parse(''), [
            'taxpayerid' => null,
            'vatcode' => null,
            'countrycode' => null,
        ]);

        $this->assertEquals(TaxNumber::parse('11111111111', TaxNumber::PART_TAXPAYERID), [
            'taxpayerid' => '11111111',
        ]);

        $this->assertEquals(TaxNumber::parse('11111111111', TaxNumber::PART_VATCODE), [
            'vatcode' => '1',
        ]);

        $this->assertEquals(TaxNumber::parse('11111111111', TaxNumber::PART_COUNTYCODE), [
            'countrycode' => '11',
        ]);

        $this->assertEquals(TaxNumber::parse('11111111111', TaxNumber::PART_TAXPAYERID | TaxNumber::PART_VATCODE), [
            'taxpayerid' => '11111111',
            'vatcode' => '1',
        ]);
    }
}

