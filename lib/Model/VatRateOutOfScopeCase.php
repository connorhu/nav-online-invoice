<?php

namespace NAV\OnlineInvoice\Model;

enum VatRateOutOfScopeCase: int
{
    /**
     * Outside the scope of VAT
     * Áfa tárgyi hatályán kívül
     *
     * @see vatOutOfScope/case/ATK
     */
    case OutsideVatScope = 1;

    /**
     * Based on section 37 of the VAT Act, a reverse charge transaction carried out in another Member State
     * Áfa tv. 37. §-a alapján másik tagállamban teljesített, fordítottan adózó ügylet
     *
     * @see vatOutOfScope/case/EUFAD37
     */
    case StateMemberReverseChargeSec37 = 2;

    /**
     * Reverse charge transaction carried out in another Member State, not subject to Section 37 of the VAT Act
     * Másik tagállamban teljesített, nem az Áfa tv. 37. §-a alá tartozó, fordítottan adózó ügylet
     *
     * @see vatOutOfScope/case/EUFADE
     */
    case StateMemberReverseCharge = 3;

    /**
     * Non-reverse charge transaction performed in another Member State
     * Másik tagállamban teljesített, nem fordítottan adózó ügylet
     *
     * @see vatOutOfScope/case/EUE
     */
    case StateMemberNonReverseCharge = 4;

    /**
     * Transaction in a third country
     * Harmadik országban teljesített ügylet
     *
     * @see vatOutOfScope/case/HO
     */
    case NonStateMemberTransaction = 5;

    /**
     * it can be used for modifying and cancelling invoices referencing a pre-3.0 invoice or without any background, if the value cannot be established.
     * 3.0 előtti számlára hivatkozó, illetve előzmény nélküli módosító és sztornó számlák esetén használható, ha nem megállapítható az érték.
     *
     * @see vatOutOfScope/case/UNKNOWN
     */
    case Unknown = 6;

    public function toString(): string
    {
        return match ($this) {
            self::OutsideVatScope => 'ATK',
            self::StateMemberReverseChargeSec37 => 'EUFAD37',
            self::StateMemberReverseCharge => 'EUFADE',
            self::StateMemberNonReverseCharge => 'EUE',
            self::NonStateMemberTransaction => 'HO',
            self::Unknown => 'UNKNOWN',
        };
    }

    public static function initWithString(?string $stringValue): ?VatRateOutOfScopeCase
    {
        if ($stringValue === null || $stringValue === '') {
            return null;
        }

        return match ($stringValue) {
            'ATK' => VatRateOutOfScopeCase::OutsideVatScope,
            'EUFAD37' => VatRateOutOfScopeCase::StateMemberReverseChargeSec37,
            'EUFADE' => VatRateOutOfScopeCase::StateMemberReverseCharge,
            'EUE' => VatRateOutOfScopeCase::StateMemberNonReverseCharge,
            'HO' => VatRateOutOfScopeCase::NonStateMemberTransaction,
            'UNKNOWN' => VatRateOutOfScopeCase::Unknown,
            default => throw new \InvalidArgumentException(sprintf('Unknown VatRateOutOfScopeCase value: "%s"', $stringValue)),
        };
    }
}
