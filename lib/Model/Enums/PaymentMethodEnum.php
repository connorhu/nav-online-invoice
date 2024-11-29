<?php

namespace NAV\OnlineInvoice\Model\Enums;

enum PaymentMethodEnum: int
{
    case Transfer = 1;
    case Cash = 2;
    case Card = 3;
    case Voucher = 4;
    case Other = 5;

    public function rawString(): string
    {
        return match ($this) {
            self::Transfer => 'TRANSFER',
            self::Cash => 'CASH',
            self::Card => 'CARD',
            self::Voucher => 'VOUCHER',
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
