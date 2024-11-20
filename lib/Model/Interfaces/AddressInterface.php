<?php

namespace NAV\OnlineInvoice\Model\Interfaces;

interface AddressInterface
{
    public function setCountryCode(string $countryCode);

    public function getCountryCode();

    public function setRegion(string $region);

    public function getRegion();

    public function setPostalCode(string $postalCode);

    public function getPostalCode();

    public function setCity(string $city);

    public function getCity();
}
