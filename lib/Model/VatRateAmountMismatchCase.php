<?php

namespace NAV\OnlineInvoice\Model;

enum VatRateAmountMismatchCase: int
{
    /**
     * TODO EN
     * Az áfa felszámítása a 11. vagy 14. § alapján történt és az áfát a számla címzettjének meg kell térítenie.
     */
    case RefundableVat = 1;

    /**
     * TODO EN
     * Az áfa felszámítása a 11. vagy 14. § alapján történt és az áfát a számla címzettjének nem kell megtérítenie.
     */
    case NonRefundableVat = 2;

    /**
     * TODO EN
     * 3.0 előtti számlára hivatkozó, illetve előzmény nélküli módosító és sztornó számlák esetén használható, ha nem megállapítható az érték.
     */
    case Unknown = 3;

    public function toString(): ?string
    {
        return match ($this) {
            self::RefundableVat => 'REFUNDABLE_VAT',
            self::NonRefundableVat => 'NONREFUNDABLE_VAT',
            self::Unknown => 'UNKNOWN',
        };
    }

    public static function initWithString(?string $stringValue): ?VatRateAmountMismatchCase
    {
        if ($stringValue === null || $stringValue === '') {
            return null;
        }

        return match ($stringValue) {
            'REFUNDABLE_VAT' => self::RefundableVat,
            'NONREFUNDABLE_VAT' => self::NonRefundableVat,
            'UNKNOWN' => self::Unknown,
            default => throw new \InvalidArgumentException(sprintf('Unknown VatRateAmountMismatchCase value: "%s"', $stringValue)),
        };
    }
}
