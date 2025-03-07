<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

class BooleanNormalizer
{
    public static function normalize(bool $value): string
    {
        return $value ? 'true' : 'false';
    }


    public static function denormalize(string $value): string
    {
        return $value === 'true' || $value === '1';
    }
}
