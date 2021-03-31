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
            $addressData['base:countryCode'] = $address->getCountryCode();
        }

        if ($address->getRegion()) {
            $addressData['base:region'] = $address->getRegion();
        }

        if ($address->getPostalCode()) {
            $addressData['base:postalCode'] = $address->getPostalCode();
        }

        if ($address->getCity()) {
            $addressData['base:city'] = $address->getCity();
        }

        if ($address->getAdditionalAddressDetail()) {
            $addressData['base:additionalAddressDetail'] = $address->getAdditionalAddressDetail();
        }

        if ($address->getStreetName()) {
            $addressData['base:streetName'] = $address->getStreetName();
        }

        if ($address->getPublicPlaceCategory()) {
            $addressData['base:publicPlaceCategory'] = $address->getPublicPlaceCategory();
        }

        if ($address->getNumber()) {
            $addressData['base:number'] = $address->getNumber();
        }

        if ($address->getFloor()) {
            $addressData['base:floor'] = $address->getFloor();
        }

        if ($address->getDoor()) {
            $addressData['base:door'] = $address->getDoor();
        }

        if ($address->getBuilding()) {
            $addressData['base:building'] = $address->getBuilding();
        }

        if ($address->getLotNumber()) {
            $addressData['base:lotNumber'] = $address->getLotNumber();
        }

        if ($address->getAdditionalAddressDetail()) {
            $buffer['base:simpleAddress'] = $addressData;
        }
        else {
            $buffer['base:detailedAddress'] = $addressData;
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
