<?php

namespace NAV\OnlineInvoice\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TaxNumber extends Constraint
{
    public const INVALID_LENGTH_ERROR = '87402298-53d4-11ea-8d77-2e728ce88125';
    public const INVALID_TAXPAYER_FORMAT_ERROR = 'd571cfc9-2faf-4894-b39a-522788541d2f';
    public const INVALID_TAXPAYER_SHORT_ERROR = '5e73faf6-6007-4f48-99cc-06073411c5b4';
    public const INVALID_VAT_CODE_FORMAT_ERROR = '80914cd0-8e58-4b66-81e8-9fed53cc78ee';
    public const MISSING_VAT_CODE_ERROR = 'c13c5006-4ac7-11ed-b878-0242ac120002';
    public const NOT_ALLOWED_VAT_CODE_ERROR = 'f138adfe-4acc-11ed-b878-0242ac120002';
    public const INVALID_COUNTRY_CODE_FORMAT_ERROR = 'edcdf373-1150-4fb3-aab6-a07b7b9315a9';
    public const MISSING_COUNTRY_CODE_ERROR = '4565f8f0-4ac8-11ed-b878-0242ac120002';
    public const NOT_ALLOWED_COUNTRY_CODE_ERROR = 'f612b87e-4acc-11ed-b878-0242ac120002';

    protected static $errorNames = [
        self::INVALID_LENGTH_ERROR => 'INVALID_LENGTH_ERROR',
        self::INVALID_TAXPAYER_FORMAT_ERROR => 'INVALID_TAXPAYER_FORMAT_ERROR',
        self::INVALID_TAXPAYER_SHORT_ERROR => 'INVALID_TAXPAYER_LENGTH_ERROR',
        self::INVALID_VAT_CODE_FORMAT_ERROR => 'INVALID_VAT_CODE_FORMAT_ERROR',
        self::MISSING_VAT_CODE_ERROR => 'MISSING_VAT_CODE_ERROR',
        self::NOT_ALLOWED_VAT_CODE_ERROR => 'NOT_ALLOWED_VAT_CODE_ERROR',
        self::INVALID_COUNTRY_CODE_FORMAT_ERROR => 'INVALID_COUNTRY_CODE_FORMAT_ERROR',
        self::MISSING_COUNTRY_CODE_ERROR => 'MISSING_COUNTRY_CODE_ERROR',
        self::NOT_ALLOWED_COUNTRY_CODE_ERROR => 'NOT_ALLOWED_COUNTRY_CODE_ERROR',
    ];
    
    public string $messageLength = 'The Tax Number "{{ value }}" is too long. The maximum length is 11 characters.';
    public string $messageTaxpayerId = 'The Taxpayer ID "{{ value }}" contains an illegal character: it can only contain numbers.';
    public string $messageTaxpayerIdShort = 'The Taxpayer ID "{{ value }}" is too short. The minimum length is 8 characters.';
    public string $messageVatCodeInvalid = 'The VAT Code "{{ vat_code }}" of the Tax Number "{{ value }}" is invalid.';
    public string $messageVatCodeMissing = 'The VAT Code of the Tax Number "{{ value }}" is missing.';
    public string $messageVatCodeNotAllowed = 'The VAT Code "{{ vat_code }}" of the Tax Number "{{ value }}" is not allowed. Allowed VAT Codes are: "{{ allowed_vat_codes }}"';
    public string $messageCountryCodeInvalid = 'The Country Code "{{ country_code }}" of the Tax Number "{{ value }}" is invalid.';
    public string $messageCountryCodeMissing = 'The Country Code of the Tax Number "{{ value }}" is missing.';
    public string $messageCountryCodeNotAllowed = 'The Country Code "{{ country_code }}" of the Tax Number "{{ value }}" is not allowed. Allowed VAT Codes are: "{{ allowed_country_codes }}"';

    public bool $vatCodeRequired = false;
    public bool $countryCodeRequired = false;

    public ?array $allowedVatCodes = null;
    public ?array $allowedCountryCodes = null;
    
    public function validatedBy(): string
    {
        return \get_class($this).'Validator';
    }
}