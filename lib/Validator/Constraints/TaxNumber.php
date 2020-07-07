<?php

namespace NAV\OnlineInvoice\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TaxNumber extends Constraint
{
    const INVALID_LENGTH_ERROR = '87402298-53d4-11ea-8d77-2e728ce88125';
    const INVALID_TAXPAYER_FORMAT_ERROR = 'd571cfc9-2faf-4894-b39a-522788541d2f';
    const INVALID_VATCODE_FORMAT_ERROR = '80914cd0-8e58-4b66-81e8-9fed53cc78ee';
    const INVALID_COUNTRYCODE_FORMAT_ERROR = 'edcdf373-1150-4fb3-aab6-a07b7b9315a9';

    protected static $errorNames = [
        self::INVALID_LENGTH_ERROR => 'INVALID_LENGTH_ERROR',
        self::INVALID_TAXPAYER_FORMAT_ERROR => 'INVALID_TAXPAYER_FORMAT_ERROR',
        self::INVALID_VATCODE_FORMAT_ERROR => 'INVALID_VATCODE_FORMAT_ERROR',
        self::INVALID_COUNTRYCODE_FORMAT_ERROR => 'INVALID_COUNTRYCODE_FORMAT_ERROR',
    ];
    
    public $messageLength = 'The string "{{ value }}" contains an illegal character: it can only contain letters or numbers.';
    public $messageTaxpayerId = 'The string "{{ value }}" contains an illegal character: it can only contain letters or numbers.';
    public $messageVatCode = 'The string "{{ value }}" contains an illegal character: it can only contain letters or numbers.';
    public $messageCountryCode = 'The string "{{ value }}" contains an illegal character: it can only contain letters or numbers.';
    
    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }
}