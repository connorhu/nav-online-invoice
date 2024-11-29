<?php

namespace NAV\OnlineInvoice\Model\Enums;

enum ProductCodeCategoryEnum: int
{
    case Vtsz = 1;
    case Szj = 2;
    case Kn = 3;
    case Ahk = 4;
    case Csk = 5;
    case Kt = 6;
    case Ej = 7;
    case Own = 8;
    case Other = 9;

    public function rawString(): string
    {
        return match ($this) {
            self::Vtsz => 'VTSZ',
            self::Szj => 'SZJ',
            self::Kn => 'KN',
            self::Ahk => 'AHK',
            self::Csk => 'CSK',
            self::Kt => 'KT',
            self::Ej => 'EJ',
            self::Own => 'OWN',
            self::Other => 'OTHER',
        };
    }

    public static function initWithRawString(string $rawString): PaymentMethodEnum
    {
        return match ($rawString) {
            'TRANSFER' => self::Transfer,
            'CASH' => self::Cash,
            'CARD' => self::Card,
            'VOUCHER' => self::Voucher,
            'OTHER' => self::Other,
        };
    }
}
