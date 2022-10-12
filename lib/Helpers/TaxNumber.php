<?php

namespace NAV\OnlineInvoice\Helpers;

class TaxNumber
{
    public const PART_TAXPAYER_ID = 1 << 2;
    public const PART_VAT_CODE = 1 << 3;
    public const PART_COUNTY_CODE = 1 << 4;

    /**
     * @param string $taxNumber
     * @param int $component
     * @return array{
     *     taxpayer_id: ?string,
     *     vat_code: ?string,
     *     country_code: ?string
     * }
     */
    public static function parse(string $taxNumber, int $component = -1): array
    {
        $buffer = [];
        if ($component === -1 || $component & self::PART_TAXPAYER_ID) {
            $part = substr($taxNumber, 0, 8);
            
            if (strlen($part) === 0) {
                $part = null;
            }
            
            $buffer['taxpayer_id'] = $part;
        }

        if ($component === -1 || $component & self::PART_VAT_CODE) {
            $part = substr($taxNumber, 8, 1);
            
            if (strlen($part) === 0) {
                $part = null;
            }
            
            $buffer['vat_code'] = $part;
        }

        if ($component === -1 || $component & self::PART_COUNTY_CODE) {
            $part = substr($taxNumber, 9, 2);
            
            if (strlen($part) === 0) {
                $part = null;
            }
            
            $buffer['country_code'] = $part;
        }
        
        return $buffer;
    }
    
    static public function isGroupTaxNumber(string $taxNumber) : bool
    {
        $parts = self::parse($taxNumber, self::PART_VAT_CODE);
        return $parts['vat_code'] === '5';
    }
    
    static public function isGroupMemberTaxNumber(string $taxNumber) : bool
    {
        $parts = self::parse($taxNumber, self::PART_VAT_CODE);
        return $parts['vat_code'] === '4';
    }
}