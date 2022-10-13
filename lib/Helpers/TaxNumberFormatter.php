<?php

namespace NAV\OnlineInvoice\Helpers;

class TaxNumberFormatter
{
    public static function formatHU(?string $taxNumber): string
    {
        if (null === $taxNumber) {
            return '';
        }

        $taxNumberLength = strlen($taxNumber);

        $formatted = substr($taxNumber, 0, 8);

        if ($taxNumberLength > 8) {
            $formatted .= ('-'.substr($taxNumber, 8, 1));
        }

        if ($taxNumberLength > 9) {
            $formatted .= ('-'.substr($taxNumber, 9));
        }

        return $formatted;
    }
}