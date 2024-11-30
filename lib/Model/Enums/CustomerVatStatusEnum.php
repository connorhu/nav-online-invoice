<?php

namespace NAV\OnlineInvoice\Model\Enums;

enum CustomerVatStatusEnum: int
{
    case Domestic = 1;
    case Other = 2;
    case PrivatePerson = 3;

    public function rawString(): string
    {
        return match ($this) {
            self::Domestic => 'DOMESTIC',
            self::Other => 'OTHER',
            self::PrivatePerson => 'PRIVATE_PERSON',
        };
    }

    public static function initWithRawString(string $rawString): self
    {
        return match ($rawString) {
            'DOMESTIC' => self::Domestic,
            'OTHER' => self::Other,
            'PRIVATE_PERSON' => self::PrivatePerson,
        };
    }
}
