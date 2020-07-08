<?php

namespace NAV\OnlineInvoice\Entity\Interfaces;

interface AddressInterface
{
    public function setCountryCode(string $value);

    public function getCountryCode();

    public function setRegion(string $value);

    public function getRegion();

    public function setPostalCode(string $value);

    public function getPostalCode();

    public function setCity(string $value);

    public function getCity();
}
