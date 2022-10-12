<?php

namespace NAV\OnlineInvoice\Validator\Constraints;

use NAV\OnlineInvoice\Helpers\TaxNumber as TaxNumberHelper;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class TaxNumberValidator extends ConstraintValidator
{
    public static array $countryCodes = [
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

        if (!preg_match('/^[0-9]{8}$/', $components['taxpayer_id'], $matches)) {
            $this->context->buildViolation($constraint->messageTaxpayerId)
                ->setParameter('{{ value }}', $value)
                ->setCode(TaxNumber::INVALID_TAXPAYER_FORMAT_ERROR)
                ->addViolation();
        }

        if ($components['vat_code'] !== null && !preg_match('/^[1-5]$/', $components['vat_code'], $matches)) {
            $this->context->buildViolation($constraint->messageVatCode)
                ->setParameter('{{ value }}', $value)
                ->setCode(TaxNumber::INVALID_VATCODE_FORMAT_ERROR)
                ->addViolation();
        }

        if ($components['country_code'] !== null && !isset(self::$countryCodes[$components['country_code']])) {
            $this->context->buildViolation($constraint->messageCountryCode)
                ->setParameter('{{ value }}', $value)
                ->setCode(TaxNumber::INVALID_COUNTRYCODE_FORMAT_ERROR)
                ->addViolation();
        }
    }
}