<?php

namespace NAV\OnlineInvoice\Model\Enums;

enum VatRateExemptionCase: int
{
    /**
     * Personal tax exemption
     * Alanyi adómentes
     *
     * @see vatExemption/case/AAM
     */
    case PersonalTaxExemption = 1;

    /**
     * “tax-exempt activity” or tax-exempt due to being in public interest or special in nature
     * „tárgyi adómentes” ill. a tevékenység közérdekű vagy speciális jellegére tekintettel adómentes
     *
     * @see vatExemption/case/TAM
     */
    case TaxExemptActivity = 2;

    /**
     * intra-Community exempt supply, without new means of transport
     * adómentes Közösségen belüli termékértékesítés, új közlekedési eszköz nélkül
     *
     * @see vatExemption/case/KBAET
     */
    case IntraCommunityExemptSupply = 3;

    /**
     * tax-exempt, intra-Community sales of new means of transport
     * adómentes Közösségen belüli új közlekedési eszköz értékesítés
     *
     * @see vatExemption/case/KBAUK
     */
    case IntraCommunityNewMeansOfTransport = 4;

    /**
     * tax-exempt, extra-Community sales of goods (export of goods to a non-EU country)
     * adómentes termékértékesítés a Közösség területén kívülre (termékexport harmadik országba)
     *
     * @see vatExemption/case/EAM
     */
    case NonEUCountryGoodsSale = 5;

    /**
     * tax-exempt on other grounds related to international transactions
     * egyéb nemzetközi ügyletekhez kapcsolódó jogcímen megállapított adómentesség
     *
     * @see vatExemption/case/NAM
     */
    case OtherInternationalTransactions = 6;

    /**
     * it can be used for modifying and cancelling invoices referencing a pre-3.0 invoice or without any background, if the value cannot be established.
     * 3.0 előtti számlára hivatkozó, illetve előzmény nélküli módosító és sztornó számlák esetén használható, ha nem megállapítható az érték.
     *
     * @see vatExemption/case/UNKNOWN
     */
    case Unknown = 7;

    public function toString(): string
    {
        return match ($this) {
            self::PersonalTaxExemption => 'AAM',
            self::TaxExemptActivity => 'TAM',
            self::IntraCommunityExemptSupply => 'KBAET',
            self::IntraCommunityNewMeansOfTransport => 'KBAUK',
            self::NonEUCountryGoodsSale => 'EAM',
            self::OtherInternationalTransactions => 'NAM',
            self::Unknown => 'UNKNOWN',
        };
    }

    public static function initWithString(?string $stringValue): ?VatRateExemptionCase
    {
        if ($stringValue === null || $stringValue === '') {
            return null;
        }

        return match ($stringValue) {
            'AAM' => self::PersonalTaxExemption,
            'TAM' => self::TaxExemptActivity,
            'KBAET' => self::IntraCommunityExemptSupply,
            'KBAUK' => self::IntraCommunityNewMeansOfTransport,
            'EAM' => self::NonEUCountryGoodsSale,
            'NAM' => self::OtherInternationalTransactions,
            'UNKNOWN' => self::Unknown,
            default => throw new \InvalidArgumentException(sprintf('Unknown VatRateExemptionCase value: "%s"', $stringValue)),
        };
    }
}
