<?php

namespace NAV\OnlineInvoice\Model;

use NAV\OnlineInvoice\Model\Enums\InvoiceOperationEnum;
use NAV\OnlineInvoice\Model\Interfaces\InvoiceInterface;
use Symfony\Component\Validator\Constraints as Assert;

class InvoiceOperation
{
    /**
     * Hash algorithm accepted for electronicInvoiceHash in every case
     * (the only one accepted when the invoice's completenessIndicator is true).
     */
    public const ELECTRONIC_INVOICE_HASH_SHA3_512 = 'SHA3-512';

    /**
     * Hash algorithm accepted for electronicInvoiceHash only when the invoice's
     * completenessIndicator is false.
     */
    public const ELECTRONIC_INVOICE_HASH_SHA_256 = 'SHA-256';

    /**
     * Hash of the electronic invoice (or modification document) file.
     *
     * Sent as ManageInvoiceRequest/invoiceOperations/invoiceOperation/electronicInvoiceHash
     * (InvoiceOperationType in invoiceApi.xsd), outside of the invoiceData, and is not
     * part of the request signature.
     */
    protected ?string $electronicInvoiceHash = null;

    /**
     * The cryptoType attribute of electronicInvoiceHash.
     */
    #[Assert\Choice(choices: [self::ELECTRONIC_INVOICE_HASH_SHA3_512, self::ELECTRONIC_INVOICE_HASH_SHA_256])]
    protected string $electronicInvoiceHashCryptoType = self::ELECTRONIC_INVOICE_HASH_SHA3_512;

    public function __construct(
        #[Assert\NotBlank]
        protected InvoiceOperationEnum $operation,
        #[Assert\Valid]
        protected InvoiceInterface $invoice
    ) {
    }

    /**
     * @return InvoiceOperationEnum
     */
    public function getOperation(): InvoiceOperationEnum
    {
        return $this->operation;
    }

    /**
     * @param InvoiceOperationEnum $operation
     * @return self
     */
    public function setOperation(InvoiceOperationEnum $operation): self
    {
        $this->operation = $operation;
        return $this;
    }

    /**
     * @return InvoiceInterface
     */
    public function getInvoice(): InvoiceInterface
    {
        return $this->invoice;
    }

    /**
     * @param InvoiceInterface $invoice
     * @return self
     */
    public function setInvoice(InvoiceInterface $invoice): self
    {
        $this->invoice = $invoice;
        return $this;
    }

    /**
     * Sets the hash of the electronic invoice file.
     *
     * NAV requires it when the invoice's completenessIndicator is true; then the only
     * accepted algorithm is SHA3-512 and the hash must be sent in upper case. Otherwise
     * SHA3-512 and SHA-256 are accepted.
     *
     * @param string|null $hash the hexadecimal hash, null removes it
     * @param string $cryptoType one of the ELECTRONIC_INVOICE_HASH_* constants
     */
    public function setElectronicInvoiceHash(?string $hash, string $cryptoType = self::ELECTRONIC_INVOICE_HASH_SHA3_512): self
    {
        $this->electronicInvoiceHash = $hash;
        $this->electronicInvoiceHashCryptoType = $cryptoType;
        return $this;
    }

    public function getElectronicInvoiceHash(): ?string
    {
        return $this->electronicInvoiceHash;
    }

    public function getElectronicInvoiceHashCryptoType(): string
    {
        return $this->electronicInvoiceHashCryptoType;
    }
}
