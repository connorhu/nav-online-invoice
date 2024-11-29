<?php

namespace NAV\OnlineInvoice\Validator\Exceptions;

class InvalidXMLException extends \RuntimeException
{
    public function __construct(private readonly array $libXmlErrors, private readonly string $xmlContent)
    {
        parent::__construct('Invalid XML');
    }

    /**
     * @return array
     */
    public function getLibXmlErrors(): array
    {
        return $this->libXmlErrors;
    }

    /**
     * @return string
     */
    public function getXmlContent(): string
    {
        return $this->xmlContent;
    }
}
