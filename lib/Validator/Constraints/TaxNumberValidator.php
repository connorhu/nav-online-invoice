<?php

namespace NAV\OnlineInvoice\Validator\Constraints;

use NAV\OnlineInvoice\Helpers\TaxNumber as TaxNumberHelper;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class TaxNumberValidator extends ConstraintValidator
{
    public static array $countyCodes = [
        '02' => 'Baranya',
        '22' => 'Baranya',
        '03' => 'Bács-Kiskun',
        '23' => 'Bács-Kiskun',
        '04' => 'Békés',
        '24' => 'Békés',
        '05' => 'Borsod-Abaúj-Zemplén',
        '25' => 'Borsod-Abaúj-Zemplén',
        '06' => 'Csongrád',
        '26' => 'Csongrád',
        '07' => 'Fejér',
        '27' => 'Fejér',
        '08' => 'Győr-Moson-Sopron',
        '28' => 'Győr-Moson-Sopron',
        '09' => 'Hajdú-Bihar',
        '29' => 'Hajdú-Bihar',
        '10' => 'Heves',
        '30' => 'Heves',
        '11' => 'Komárom-Esztergom',
        '31' => 'Komárom-Esztergom',
        '12' => 'Nógrád',
        '32' => 'Nógrád',
        '13' => 'Pest',
        '33' => 'Pest',
        '14' => 'Somogy',
        '34' => 'Somogy',
        '15' => 'Szabolcs-Szatmár-Bereg',
        '35' => 'Szabolcs-Szatmár-Bereg',
        '16' => 'Jász-Nagykun-Szolnok',
        '36' => 'Jász-Nagykun-Szolnok',
        '17' => 'Tolna',
        '37' => 'Tolna',
        '18' => 'Vas',
        '38' => 'Vas',
        '19' => 'Veszprém',
        '39' => 'Veszprém',
        '20' => 'Zala',
        '40' => 'Zala',
        '41' => 'Észak-Budapest',
        '42' => 'Kelet-Budapest',
        '43' => 'Dél-Budapest',
        '44' => 'Kiemelt Adózók Adóigazgatósága',
        '51' => 'Kiemelt Ügyek Adóigazgatósága',
    ];

    public static array $vatCodes = [
        1 => '',
        2 => '',
        3 => '',
        4 => '',
        5 => '',
    ];
    
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof TaxNumber) {
            throw new UnexpectedTypeException($constraint, TaxNumber::class);
        }
        
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (strlen($value) > 11) {
            $this->context->buildViolation($constraint->messageLength)
                ->setParameter('{{ value }}', $value)
                ->setCode(TaxNumber::INVALID_LENGTH_ERROR)
                ->addViolation();
        }
        
        $components = TaxNumberHelper::parse($value);

        if (strlen($components['taxpayer_id']) !== 8) {
            $this->context->buildViolation($constraint->messageTaxpayerIdShort)
                ->setParameter('{{ value }}', $components['taxpayer_id'])
                ->setCode(TaxNumber::INVALID_TAXPAYER_SHORT_ERROR)
                ->addViolation();
        } elseif (!preg_match('/^[0-9]{8}$/', $components['taxpayer_id'], $matches)) {
            $this->context->buildViolation($constraint->messageTaxpayerId)
                ->setParameter('{{ value }}', $value)
                ->setCode(TaxNumber::INVALID_TAXPAYER_FORMAT_ERROR)
                ->addViolation();
        }

        if ($components['vat_code'] !== null && !preg_match('/^[1-5]$/', $components['vat_code'], $matches)) {
            $this->context->buildViolation($constraint->messageVatCodeInvalid)
                ->setParameter('{{ value }}', $value)
                ->setParameter('{{ vat_code }}', $components['vat_code'])
                ->setCode(TaxNumber::INVALID_VAT_CODE_FORMAT_ERROR)
                ->addViolation();
        } elseif ($components['vat_code'] !== null && $constraint->allowedVatCodes !== null) {
            if (0 === \count($constraint->allowedVatCodes)) {
                throw new InvalidOptionsException('The allowedVatCodes field must contain one valid VAT Code at least.', self::$vatCodes);
            }

            foreach ($constraint->allowedVatCodes as $allowedVatCode) {
                if (!isset(self::$vatCodes[(int)$allowedVatCode])) {
                    throw new InvalidOptionsException('The allowedVatCodes field must contain valid VAT Codes.', self::$vatCodes);
                }
            }

            if (!in_array((int)$components['vat_code'], $constraint->allowedVatCodes)) {
                $this->context->buildViolation($constraint->messageVatCodeNotAllowed)
                    ->setParameter('{{ value }}', $value)
                    ->setParameter('{{ vat_code }}', $components['vat_code'])
                    ->setParameter('{{ allowed_vat_codes }}', implode(', ', $constraint->allowedVatCodes))
                    ->setCode(TaxNumber::NOT_ALLOWED_VAT_CODE_ERROR)
                    ->addViolation();
            }

        } elseif ($components['vat_code'] === null && $constraint->vatCodeRequired === true) {
            $this->context->buildViolation($constraint->messageVatCodeMissing)
                ->setParameter('{{ value }}', $value)
                ->setCode(TaxNumber::MISSING_VAT_CODE_ERROR)
                ->addViolation();
        }

        if ($components['county_code'] !== null && !isset(self::$countyCodes[$components['county_code']])) {
            $this->context->buildViolation($constraint->messageCountyCodeInvalid)
                ->setParameter('{{ value }}', $value)
                ->setParameter('{{ county_code }}', $components['county_code'])
                ->setCode(TaxNumber::INVALID_COUNTY_CODE_FORMAT_ERROR)
                ->addViolation();
        } elseif ($components['county_code'] !== null && $constraint->allowedCountyCodes !== null) {
            if (0 === \count($constraint->allowedCountyCodes)) {
                throw new InvalidOptionsException('The allowedCountyCodes field must contain one valid County Code at least.', array_keys(self::$countyCodes));
            }

            foreach ($constraint->allowedCountyCodes as $allowedCountyCode) {
                if (!isset(self::$countyCodes[$allowedCountyCode])) {
                    throw new InvalidOptionsException('The allowedCountyCodes field must contain valid County Codes.', array_keys(self::$countyCodes));
                }
            }

            if (!in_array((int)$components['county_code'], $constraint->allowedCountyCodes)) {
                $this->context->buildViolation($constraint->messageCountyCodeNotAllowed)
                    ->setParameter('{{ value }}', $value)
                    ->setParameter('{{ county_code }}', $components['county_code'])
                    ->setParameter('{{ allowed_county_codes }}', implode(', ', $constraint->allowedCountyCodes))
                    ->setCode(TaxNumber::NOT_ALLOWED_COUNTY_CODE_ERROR)
                    ->addViolation();
            }

        } elseif ($components['county_code'] === null && $constraint->countyCodeRequired === true) {
            $this->context->buildViolation($constraint->messageCountyCodeMissing)
                ->setParameter('{{ value }}', $value)
                ->setCode(TaxNumber::MISSING_COUNTY_CODE_ERROR)
                ->addViolation();
        }
    }
}
