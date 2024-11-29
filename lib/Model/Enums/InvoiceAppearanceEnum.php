<?php

namespace NAV\OnlineInvoice\Model\Enums;

enum InvoiceAppearanceEnum: int
{
    case Paper = 1;
    case Electronic = 2;
    case Edi = 3;
    case Unknown = 4;

    public function rawString(): string
    {
        return match ($this) {
            self::Paper => 'PAPER',
            self::Electronic => 'ELECTRONIC',
            self::Edi => 'EDI',
            self::Unknown => 'UNKNOWN',
        };
    }

    public static function initWithRawString(string $rawString): InvoiceAppearanceEnum
    {
        return match ($rawString) {
            'PAPER' => self::Paper,
            'ELECTRONIC' => self::Electronic,
            'EDI' => self::Edi,
            'UNKNOWN' => self::Unknown,
        };
    }
}
