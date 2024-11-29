<?php

namespace NAV\OnlineInvoice\Validator;

class XSDValidator
{
    /**
     * @var array
     */
    public array $errors = [];

    private \DOMDocument $domHandler;

    public function __construct(private readonly string $schemaFile)
    {
        $this->domHandler = new \DOMDocument('1.0', 'utf-8');
    }

    /**
     * @param string $xmlString
     */
    public function validate(string $xmlString): void
    {
        libxml_use_internal_errors(true);

        $this->domHandler->loadXML($xmlString, LIBXML_NOBLANKS);
        if (!@$this->domHandler->schemaValidate($this->schemaFile)) {
            $this->errors = libxml_get_errors();
        }
    }
    /**
     * @return array<int, \LibXMLError>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}

