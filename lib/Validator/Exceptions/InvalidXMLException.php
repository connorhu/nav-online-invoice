<?php

namespace NAV\OnlineInvoice\Validator\Exceptions;

class InvalidXMLException extends \RuntimeException
{
    private array $libXmlErrors;

    public function __construct(array $libXmlErrors)
    {
        $this->libXmlErrors = $libXmlErrors;

        parent::__construct('Invalid XML');
    }

    /**
     * @return array
     */
    public function getLibXmlErrors(): array
    {
        return $this->libXmlErrors;
    }
}
