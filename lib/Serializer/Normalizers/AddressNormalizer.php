<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Entity\Address;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AddressNormalizer implements ContextAwareNormalizerInterface, SerializerAwareInterface
{
    public function normalize($address, $format = null, array $context = [])
    {
        $buffer = [];
        
        if ($address->getCountryCode()) {
            $addressData['countryCode'] = $address->getCountryCode();
        }

        if ($address->getRegion()) {
            $addressData['region'] = $address->getRegion();
        }

        if ($address->getPostalCode()) {
            $addressData['postalCode'] = $address->getPostalCode();
        }

        if ($address->getCity()) {
            $addressData['city'] = $address->getCity();
        }

        if ($address->getAdditionalAddressDetail()) {
            $addressData['additionalAddressDetail'] = $address->getAdditionalAddressDetail();
        }

        if ($address->getStreetName()) {
            $addressData['streetName'] = $address->getStreetName();
        }

        if ($address->getPublicPlaceCategory()) {
            $addressData['publicPlaceCategory'] = $address->getPublicPlaceCategory();
        }

        if ($address->getNumber()) {
            $addressData['number'] = $address->getNumber();
        }

        if ($address->getFloor()) {
            $addressData['floor'] = $address->getFloor();
        }

        if ($address->getDoor()) {
            $addressData['door'] = $address->getDoor();
        }

        if ($address->getBuilding()) {
            $addressData['building'] = $address->getBuilding();
        }

        if ($address->getLotNumber()) {
            $addressData['lotNumber'] = $address->getLotNumber();
        }

        if ($address->getAdditionalAddressDetail()) {
            $buffer['simpleAddress'] = $addressData;
        }
        else {
            $buffer['detailedAddress'] = $addressData;
        }
        
        return $buffer;
    }
    
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Address;
    }
    
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
}
