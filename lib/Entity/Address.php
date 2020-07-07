<?php

namespace NAV\OnlineInvoice\Entity;

class Address
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
    protected $countryCode;
    
    /**
     * setter for countryCode
     *
     * @param mixed 
     * @return self
     */
    public function setCountryCode($value)
    {
        $this->countryCode = $value;
        return $this;
    }
    
    /**
     * getter for countryCode
     * 
     * @return mixed return value for 
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }
    
    /**
     * Tartomány kódja (amennyiben értelmezhető az adott országban) az ISO 3166-2 alpha 2 szabvány szerint
     * 
     * requirements: not required
     * node name: region
     * address type: simple|detailed
     * pattern: .*\S.*
     * type: SimpleText50NotBlankType
     *
     * @var string
     */
    protected $region;
    
    /**
     * setter for region
     *
     * @param mixed 
     * @return self
     */
    public function setRegion($value)
    {
        $this->region = $value;
        return $this;
    }
    
    /**
     * getter for region
     * 
     * @return mixed return value for 
     */
    public function getRegion()
    {
        return $this->region;
    }
    
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
    protected $postalCode;
    
    /**
     * setter for postalCode
     *
     * @param mixed 
     * @return self
     */
    public function setPostalCode($value)
    {
        $this->postalCode = $value;
        return $this;
    }
    
    /**
     * getter for postalCode
     * 
     * @return mixed return value for 
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }
    
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
    protected $city;
    
    /**
     * setter for city
     *
     * @param mixed 
     * @return self
     */
    public function setCity($value)
    {
        $this->city = $value;
        return $this;
    }
    
    /**
     * getter for city
     * 
     * @return mixed return value for 
     */
    public function getCity()
    {
        return $this->city;
    }
    
    /**
     * További címadatok (pl. közterület neve és jellege, házszám, emelet, ajtó, helyrajzi szám, stb.) 
     * 
     * requirements: required
     * node name: additionalAddressDetail
     * address type: simple
     * pattern: .*\S.*
     * type: SimpleText255NotBlankType
     *
     * @var string
     */
    protected $additionalAddressDetail;
    
    /**
     * setter for additionalAddressDetail
     *
     * @param mixed 
     * @return self
     */
    public function setAdditionalAddressDetail($value)
    {
        $this->additionalAddressDetail = $value;
        return $this;
    }
    
    /**
     * getter for additionalAddressDetail
     * 
     * @return mixed return value for 
     */
    public function getAdditionalAddressDetail()
    {
        return $this->additionalAddressDetail;
    }
    
    /**
     * Közterület neve
     * 
     * requirements: required
     * node name: streetName
     * address type: detailed
     * pattern: .*\S.*
     * type: SimpleText255NotBlankType
     *
     * @var string
     */
    protected $streetName;
    
    /**
     * setter for streetName
     *
     * @param mixed 
     * @return self
     */
    public function setStreetName($value)
    {
        $this->streetName = $value;
        return $this;
    }
    
    /**
     * getter for streetName
     * 
     * @return mixed return value for 
     */
    public function getStreetName()
    {
        return $this->streetName;
    }
    
    /**
     * Közterület jellege 
     * 
     * requirements: required
     * node name: publicPlaceCategory
     * address type: detailed
     * pattern: .*\S.*
     * type: SimpleText50NotBlankType
     *
     * @var string
     */
    protected $publicPlaceCategory;
    
    /**
     * setter for publicPlaceCategory
     *
     * @param mixed 
     * @return self
     */
    public function setPublicPlaceCategory($value)
    {
        $this->publicPlaceCategory = $value;
        return $this;
    }
    
    /**
     * getter for publicPlaceCategory
     * 
     * @return mixed return value for 
     */
    public function getPublicPlaceCategory()
    {
        return $this->publicPlaceCategory;
    }
    
    /**
     * Házszám
     * 
     * requirements: not required
     * node name: number
     * address type: detailed
     * pattern: .*\S.*
     * type: SimpleText50NotBlankType
     *
     * @var string
     */
    protected $number;
    
    /**
     * setter for number
     *
     * @param mixed 
     * @return self
     */
    public function setNumber($value)
    {
        $this->number = $value;
        return $this;
    }
    
    /**
     * getter for number
     * 
     * @return mixed return value for 
     */
    public function getNumber()
    {
        return $this->number;
    }
    
    /**
     * Emelet
     * 
     * requirements: not required
     * node name: floor
     * address type: detailed
     * pattern: .*\S.*
     * type: SimpleText50NotBlankType
     *
     * @var string
     */
    protected $floor;
    
    /**
     * setter for floor
     *
     * @param mixed 
     * @return self
     */
    public function setFloor($value)
    {
        $this->floor = $value;
        return $this;
    }
    
    /**
     * getter for floor
     * 
     * @return mixed return value for 
     */
    public function getFloor()
    {
        return $this->floor;
    }
    
    /**
     * Ajtó
     * 
     * requirements: not required
     * node name: door
     * address type: detailed
     * pattern: .*\S.*
     * type: SimpleText50NotBlankType
     *
     * @var string
     */
    protected $door;
    
    /**
     * setter for door
     *
     * @param mixed 
     * @return self
     */
    public function setDoor($value)
    {
        $this->door = $value;
        return $this;
    }
    
    /**
     * getter for door
     * 
     * @return mixed return value for 
     */
    public function getDoor()
    {
        return $this->door;
    }
    
    /**
     * Épület
     * 
     * requirements: not required
     * node name: building
     * address type: detailed
     * pattern: .*\S.*
     * type: SimpleText50NotBlankType
     *
     * @var string
     */
    protected $building;
    
    /**
     * setter for building
     *
     * @param mixed 
     * @return self
     */
    public function setBuilding($value)
    {
        $this->building = $value;
        return $this;
    }
    
    /**
     * getter for building
     * 
     * @return mixed return value for 
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * Helyrajzi szám
     *
     * requirements: not required
     * node name: lotNumber
     * address type: detailed
     * pattern: .*\S.*
     * type: SimpleText50NotBlankType
     *
     * @var string
     */
    protected $lotNumber;
    
    /**
     * setter for lotNumber
     *
     * @param mixed 
     * @return self
     */
    public function setLotNumber($value)
    {
        $this->lotNumber = $value;
        return $this;
    }
    
    /**
     * getter for lotNumber
     * 
     * @return mixed return value for 
     */
    public function getLotNumber()
    {
        return $this->lotNumber;
    }
}
