<?php

namespace NAV\OnlineInvoice\Model\Enums;

enum ValidationResultCodeEnum: int
{
    case Critical = 1;
    case Error = 2;
    case Warning = 3;
    case Info = 4;

    public static function initWithRawString(string $rawString): self
    {
        return match ($rawString) {
            'CRITICAL' => self::Critical,
            'ERROR' => self::Error,
            'WARN' => self::Warning,
            'INFO' => self::Info,
        };
    }
}
