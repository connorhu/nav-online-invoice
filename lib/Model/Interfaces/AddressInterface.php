<?php

namespace NAV\OnlineInvoice\Model\Interfaces;

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

class_alias(AddressInterface::class, \NAV\OnlineInvoice\Model\Interfaces\AddressInterface::class);
