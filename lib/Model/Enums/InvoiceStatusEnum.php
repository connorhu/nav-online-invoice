<?php

namespace NAV\OnlineInvoice\Model\Enums;

enum InvoiceStatusEnum: int
{
    case Received = 1;
    case Processing = 2;
    case Saved = 3;
    case Done = 4;
    case Aborted = 5;

    public static function initWithRawString(string $rawString): self
    {
        return match ($rawString) {
            'RECEIVED' => self::Received,
            'PROCESSING' => self::Processing,
            'SAVED' => self::Saved,
            'DONE' => self::Done,
            'ABORTED' => self::Aborted,
        };
    }
}
