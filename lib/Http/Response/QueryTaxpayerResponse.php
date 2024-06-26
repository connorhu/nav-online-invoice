<?php

namespace NAV\OnlineInvoice\Http\Response;

use NAV\OnlineInvoice\Http\Response;

class QueryTaxpayerResponse extends Response
{
    private $validity;
    
    private $lastUpdate;
    
    private $name;
    
    private $shortName;
    
    private $vatGroupMembership;

    // TODO incoproration in api namespace

    /**
     * @var int|null
     */
    private ?int $taxpayerId;

    /**
     * @var int|null
     */
    private ?int $vatCode;

    /**
     * @var int|null
     */
    private ?int $countryCode;

    private $addresses = [];
    
    public function setValidity(bool $validity)
    {
        $this->validity = $validity;
    }
    
    public function getValidity(): bool
    {
        return $this->validity;
    }
    
    public function setLastUpdate(\DateTime $lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
    }
    
    public function getLastUpdate(): ?\DateTime
    {
        return $this->lastUpdate;
    }
    
    public function setName(string $name)
    {
        $this->name = $name;
    }
    
    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function setTaxpayerId(int $taxpayerId)
    {
        $this->taxpayerId = $taxpayerId;
    }
    
    public function getTaxpayerId(): ?int
    {
        return $this->taxpayerId;
    }
    
    public function setVatCode(int $vatCode)
    {
        $this->vatCode = $vatCode;
    }
    
    public function getVatCode(): ?int
    {
        return $this->vatCode;
    }

    /**
     * @return mixed
     */
    public function getCountryCode(): ?int
    {
        return $this->countryCode;
    }

    /**
     * @param mixed $countryCode
     */
    public function setCountryCode(int $countryCode): void
    {
        $this->countryCode = $countryCode;
    }
    
    public function getAddresses(): array
    {
        return $this->addresses;
    }
    
    public function addAddress(array $address)
    {
        $this->addresses[] = $address;
    }
    
    public function setVatGroupMembership(int $vatGroupMembership)
    {
        $this->vatGroupMembership = $vatGroupMembership;
    }
    
    public function getVatGroupMembership(): ?int
    {
        return $this->vatGroupMembership;
    }
    
    public function setShortName(string $shortName)
    {
        $this->shortName = $shortName;
    }
    
    public function getShortName(): ?string
    {
        return $this->shortName;
    }
}