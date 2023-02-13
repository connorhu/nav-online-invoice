<?php

namespace NAV\OnlineInvoice\Http\Response;

class Audit
{
    private ?\DateTime $insertDate = null;

    private ?string $insertCustomerUser = null;

    private ?string $source = null;

    private ?string $transactionId = null;

    private ?int $index = null;

    private ?int $batchIndex = null;

    private ?string $originalRequestVersion = null;

    /**
     * @param \DateTime|null $insertDate
     */
    public function setInsertDate(?\DateTime $insertDate): void
    {
        $this->insertDate = $insertDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getInsertDate(): ?\DateTime
    {
        return $this->insertDate;
    }

    /**
     * @param string|null $insertCustomerUser
     */
    public function setInsertCustomerUser(?string $insertCustomerUser): void
    {
        $this->insertCustomerUser = $insertCustomerUser;
    }

    /**
     * @return string|null
     */
    public function getInsertCustomerUser(): ?string
    {
        return $this->insertCustomerUser;
    }

    /**
     * @param string|null $source
     */
    public function setSource(?string $source): void
    {
        $this->source = $source;
    }

    /**
     * @return string|null
     */
    public function getSource(): ?string
    {
        return $this->source;
    }

    /**
     * @param string|null $transactionId
     */
    public function setTransactionId(?string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return string|null
     */
    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    /**
     * @param int|null $index
     */
    public function setIndex(?int $index): void
    {
        $this->index = $index;
    }

    /**
     * @return int|null
     */
    public function getIndex(): ?int
    {
        return $this->index;
    }

    /**
     * @param int|null $batchIndex
     */
    public function setBatchIndex(?int $batchIndex): void
    {
        $this->batchIndex = $batchIndex;
    }

    /**
     * @return int|null
     */
    public function getBatchIndex(): ?int
    {
        return $this->batchIndex;
    }

    /**
     * @param string|null $originalRequestVersion
     */
    public function setOriginalRequestVersion(?string $originalRequestVersion): void
    {
        $this->originalRequestVersion = $originalRequestVersion;
    }

    /**
     * @return string|null
     */
    public function getOriginalRequestVersion(): ?string
    {
        return $this->originalRequestVersion;
    }
}