<?php

namespace NAV\OnlineInvoice\Model;

use NAV\OnlineInvoice\Model\Interfaces\AddressInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Address implements AddressInterface
{
    /**
     * ISO 3166
     * requirements: required
     * node name: countryCode
     * address type: simple|detailed
     * pattern: [A-Z]{2}
     * type: CountryCodeType
     *
     * @var string
     */
    #[Assert\NotBlank(groups: ['v1.0', 'v1.1', 'v2.0', 'v3.0'])]
    protected string $countryCode = '';

    /**
     * Tartomány kódja (amennyiben értelmezhető az adott országban) az ISO 3166-2 alpha 2 szabvány szerint
     *
     * requirements: not required
     * node name: region
     * address type: simple|detailed
     * pattern: .*\S.*
     * type: SimpleText50NotBlankType
     *
     * @var string|null
     */
    protected ?string $region = null;

    /**
     * Irányítószám (amennyiben nem értelmezhető, 0000 értékkel kell kitölteni)
     *
     * requirements: required
     * node name: postalCode
     * address type: simple|detailed
     * pattern: [A-Z0-9]{4,10}
     * type: PostalCodeType
     *
     * @var string
     */
    #[Assert\NotBlank(groups: ['v1.0', 'v1.1', 'v2.0', 'v3.0'])]
    protected string $postalCode = '';

    /**
     * Település
     *
     * requirements: required
     * node name: city
     * address type: simple|detailed
     * pattern: .*\S.*
     * type: SimpleText255NotBlankType
     *
     * @var string
     */
    #[Assert\NotBlank(groups: ['v1.0', 'v1.1', 'v2.0', 'v3.0'])]
    protected string $city = '';

    /**
     * További címadatok (pl. közterület neve és jellege, házszám, emelet, ajtó, helyrajzi szám, stb.)
     *
     * requirements: required
     * node name: additionalAddressDetail
     * address type: simple
     * pattern: .*\S.*
     * type: SimpleText255NotBlankType
     *
     * @var string|null
     */
    protected ?string $additionalAddressDetail = null;

    /**
     * Közterület neve
     *
     * requirements: required
     * node name: streetName
     * address type: detailed
     * pattern: .*\S.*
     * type: SimpleText255NotBlankType
     *
     * @var string|null
     */
    protected ?string $streetName = null;

    /**
     * Közterület jellege
     *
     * requirements: required
     * node name: publicPlaceCategory
     * address type: detailed
     * pattern: .*\S.*
     * type: SimpleText50NotBlankType
     *
     * @var string|null
     */
    protected ?string $publicPlaceCategory = null;

    /**
     * Házszám
     *
     * requirements: not required
     * node name: number
     * address type: detailed
     * pattern: .*\S.*
     * type: SimpleText50NotBlankType
     *
     * @var string|null
     */
    protected ?string $number = null;

    /**
     * Emelet
     *
     * requirements: not required
     * node name: floor
     * address type: detailed
     * pattern: .*\S.*
     * type: SimpleText50NotBlankType
     *
     * @var string|null
     */
    protected ?string $floor = null;

    /**
     * Ajtó
     *
     * requirements: not required
     * node name: door
     * address type: detailed
     * pattern: .*\S.*
     * type: SimpleText50NotBlankType
     *
     * @var string|null
     */
    protected ?string $door = null;

    /**
     * Épület
     *
     * requirements: not required
     * node name: building
     * address type: detailed
     * pattern: .*\S.*
     * type: SimpleText50NotBlankType
     *
     * @var string|null
     */
    protected ?string $building = null;

    /**
     * Helyrajzi szám
     *
     * requirements: not required
     * node name: lotNumber
     * address type: detailed
     * pattern: .*\S.*
     * type: SimpleText50NotBlankType
     *
     * @var string|null
     */
    protected ?string $lotNumber = null;

    /**
     * getter for countryCode
     *
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * setter for countryCode
     *
     * @param string $countryCode
     * @return self
     */
    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * getter for region
     *
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * setter for region
     *
     * @param string|null $region
     * @return self
     */
    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * getter for postalCode
     *
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * setter for postalCode
     *
     * @param string $postalCode
     * @return self
     */
    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * getter for city
     *
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * setter for city
     *
     * @param string $city
     * @return self
     */
    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * getter for additionalAddressDetail
     *
     * @return string|null
     */
    public function getAdditionalAddressDetail(): ?string
    {
        return $this->additionalAddressDetail;
    }

    /**
     * setter for additionalAddressDetail
     *
     * @param string|null $additionalAddressDetail
     * @return self
     */
    public function setAdditionalAddressDetail(?string $additionalAddressDetail): self
    {
        $this->additionalAddressDetail = $additionalAddressDetail;
        return $this;
    }

    /**
     * getter for streetName
     *
     * @return string|null
     */
    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    /**
     * setter for streetName
     *
     * @param string|null $streetName
     * @return self
     */
    public function setStreetName(?string $streetName): self
    {
        $this->streetName = $streetName;
        return $this;
    }

    /**
     * getter for publicPlaceCategory
     *
     * @return string|null
     */
    public function getPublicPlaceCategory(): ?string
    {
        return $this->publicPlaceCategory;
    }

    /**
     * setter for publicPlaceCategory
     *
     * @param string|null
     * @return self
     */
    public function setPublicPlaceCategory(?string $publicPlaceCategory): self
    {
        $this->publicPlaceCategory = $publicPlaceCategory;
        return $this;
    }

    /**
     * getter for number
     *
     * @return string|null
     */
    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * setter for number
     *
     * @param string|null
     * @return self
     */
    public function setNumber(?string $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * getter for floor
     *
     * @return string|null
     */
    public function getFloor(): ?string
    {
        return $this->floor;
    }

    /**
     * setter for floor
     *
     * @param string|null
     * @return self
     */
    public function setFloor(?string $value): self
    {
        $this->floor = $value;
        return $this;
    }

    /**
     * getter for door
     *
     * @return string|null
     */
    public function getDoor(): ?string
    {
        return $this->door;
    }

    /**
     * setter for door
     *
     * @param string|null
     * @return self
     */
    public function setDoor(?string $door): self
    {
        $this->door = $door;
        return $this;
    }

    /**
     * getter for building
     *
     * @return string|null
     */
    public function getBuilding(): ?string
    {
        return $this->building;
    }

    /**
     * setter for building
     *
     * @param string|null
     * @return self
     */
    public function setBuilding(?string $building): self
    {
        $this->building = $building;
        return $this;
    }

    /**
     * getter for lotNumber
     *
     * @return string|null
     */
    public function getLotNumber(): ?string
    {
        return $this->lotNumber;
    }

    /**
     * setter for lotNumber
     *
     * @param string|null $lotNumber
     * @return self
     */
    public function setLotNumber(?string $lotNumber): ?string
    {
        $this->lotNumber = $lotNumber;
        return $this;
    }

    public const STREET_IS_BLANK_ERROR = 'a21ec579-1c2d-475e-9ac0-4b00f8c896be';

    /**
     * @Assert\Callback(groups={"v1.0", "v1.1", "v2.0", "v3.0"})
     */
    public function validateStreetFields(ExecutionContextInterface $context)
    {
        if (empty($this->additionalAddressDetail) && (empty($this->streetName) || empty($this->publicPlaceCategory))) {
            $context->buildViolation('Either additionalAddressDetail or streetName and publicPlaceCategory field is requred.')
                ->setCode(self::STREET_IS_BLANK_ERROR)
                ->addViolation();
        }
    }
}
