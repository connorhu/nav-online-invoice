<?php

namespace NAV\OnlineInvoice\Helpers;

class TaxNumber
{
    const PART_TAXPAYERID = 1 << 2;
    const PART_VATCODE = 1 << 3;
    const PART_COUNTYCODE = 1 << 4;
        
    static public function parse(string $taxNumber, int $component = -1): array
    {
        $buffer = [];
        if ($component === -1 || $component & self::PART_TAXPAYERID) {
            $part = substr($taxNumber, 0, 8);
            
            if (!is_string($part) || strlen($part) === 0) {
                $part = null;
            }
            
            $buffer['taxpayerid'] = $part;
        }

        if ($component === -1 || $component & self::PART_VATCODE) {
            $part = substr($taxNumber, 8, 1);
            
            if (!is_string($part) || strlen($part) === 0) {
                $part = null;
            }
            
            $buffer['vatcode'] = $part;
        }

        if ($component === -1 || $component & self::PART_COUNTYCODE) {
            $part = substr($taxNumber, 9, 2);
            
            if (!is_string($part) || strlen($part) === 0) {
                $part = null;
            }
            
            $buffer['countrycode'] = $part;
        }
        
        return $buffer;
    }
    
    static public function isGroupTaxNumber(string $taxNumber) : bool
    {
        $parts = self::parse($taxNumber, self::PART_VATCODE);
        return $parts === '5';
    }
    
    static public function isGroupMemberTaxNumber(string $taxNumber) : bool
    {
        $parts = self::parse($taxNumber, self::PART_VATCODE);
        return $parts === '4';
    }
}