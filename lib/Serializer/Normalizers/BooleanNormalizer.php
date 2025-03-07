<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

class BooleanNormalizer
{
    public static function normalize($value): string
    {
        return $value ? 'true' : 'false';
    }


    public static function denormalize($value): string
    {
        return $value === 'true' || $value === '1';
    }
}
