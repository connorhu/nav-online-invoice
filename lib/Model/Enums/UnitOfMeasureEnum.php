<?php

namespace NAV\OnlineInvoice\Model\Enums;

enum UnitOfMeasureEnum: int
{
    case Piece = 1;
    case Kilogram = 2;
    case Ton = 3;
    case Kwh = 4;
    case Day = 5;
    case Hour = 6;
    case Minute = 7;
    case Month = 8;
    case Liter = 9;
    case Kilometer = 10;
    case CubicMeter = 11;
    case Meter = 12;
    case LinearMeter = 13;
    case Carton = 14;
    case Pack = 15;
    case Own = 16;

    public function rawString(): string
    {
        return match ($this) {
            self::Piece => 'PIECE',
            self::Kilogram => 'KILOGRAM',
            self::Ton => 'TON',
            self::Kwh => 'KWH',
            self::Day => 'DAY',
            self::Hour => 'HOUR',
            self::Minute => 'MINUTE',
            self::Month => 'MONTH',
            self::Liter => 'LITER',
            self::Kilometer => 'KILOMETER',
            self::CubicMeter => 'CUBIC_METER',
            self::Meter => 'METER',
            self::LinearMeter => 'LINEAR_METER',
            self::Carton => 'CARTON',
            self::Pack => 'PACK',
            self::Own => 'OWN',
        };
    }

    public static function initWithRawString(string $rawString): self
    {
        return match ($rawString) {
            'PIECE' => self::Piece,
            'KILOGRAM' => self::Kilogram,
            'TON' => self::Ton,
            'KWH' => self::Kwh,
            'DAY' => self::Day,
            'HOUR' => self::Hour,
            'MINUTE' => self::Minute,
            'MONTH' => self::Month,
            'LITER' => self::Liter,
            'KILOMETER' => self::Kilometer,
            'CUBIC_METER' => self::CubicMeter,
            'METER' => self::Meter,
            'LINEAR_METER' => self::LinearMeter,
            'CARTON' => self::Carton,
            'PACK' => self::Pack,
            'OWN' => self::Own,
        };
    }
}
