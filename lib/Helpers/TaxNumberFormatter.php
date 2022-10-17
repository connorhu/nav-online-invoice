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

    /**
     * @TODO
     * @param string|null $taxNumber
     * @return string|null
     */
    public static function formatEU(?string $taxNumber): ?string
    {
        // TODO
        return $taxNumber;
    }

    /**
     * @TODO
     * @param string|null $taxNumber
     * @param string $countryCode
     * @return string|null
     */
    public static function formatThirdState(?string $taxNumber, string $countryCode): ?string
    {
        // TODO
        return $taxNumber;
    }

    /**
     * @param string|null $taxNumber tax number to format
     * @param string $countryCode iso3166-2
     * @return string|null
     */
    public static function format(?string $taxNumber, string $countryCode): ?string
    {
        if (null === $taxNumber) {
            return null;
        }

        if ('HU' === $countryCode) {
            return self::formatHU($taxNumber);
        }

        if ('EU' === $countryCode || TaxNumberFormatter::isEUMember($countryCode)) {
            return self::formatEU($taxNumber);
        }

        return self::formatThirdState($taxNumber, $countryCode);
    }

    /**
     * @param string $countryCode iso3166-2
     * @return bool
     */
    public static function isEUMember(string $countryCode): bool
    {
        return match ($countryCode) {
            'AT', 'BE', 'BG', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'GR', 'NL', 'HR', 'IE', 'PL', 'LV', 'LT', 'LU', 'HU', 'MT', 'DE', 'IT', 'PT', 'RO', 'ES', 'SE', 'SK', 'SI' => true,
            default => false,
        };
    }
}