<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Entity\Address;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;

class AddressNormalizer implements ContextAwareNormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    public function normalize($object, $format = null, array $context = []): array
    {
        $addressData = [];

        if (!$object instanceof Address) {
            throw new \Exception('');
        }

        if ($object->getCountryCode()) {
            $addressData['base:countryCode'] = $object->getCountryCode();
        }

        if ($object->getRegion()) {
            $addressData['base:region'] = $object->getRegion();
        }

        if ($object->getPostalCode()) {
            $addressData['base:postalCode'] = $object->getPostalCode();
        }

        if ($object->getCity()) {
            $addressData['base:city'] = $object->getCity();
        }

        if ($object->getAdditionalAddressDetail()) {
            $addressData['base:additionalAddressDetail'] = $object->getAdditionalAddressDetail();
        }

        if ($object->getStreetName()) {
            $addressData['base:streetName'] = $object->getStreetName();
        }

        if ($object->getPublicPlaceCategory()) {
            $addressData['base:publicPlaceCategory'] = $object->getPublicPlaceCategory();
        }

        if ($object->getNumber()) {
            $addressData['base:number'] = $object->getNumber();
        }

        if ($object->getFloor()) {
            $addressData['base:floor'] = $object->getFloor();
        }

        if ($object->getDoor()) {
            $addressData['base:door'] = $object->getDoor();
        }

        if ($object->getBuilding()) {
            $addressData['base:building'] = $object->getBuilding();
        }

        if ($object->getLotNumber()) {
            $addressData['base:lotNumber'] = $object->getLotNumber();
        }

        if ($object->getAdditionalAddressDetail()) {
            $buffer = [
                'base:simpleAddress' => $addressData,
            ];
        } else {
            $buffer = [
                'base:detailedAddress' => $addressData,
            ];
        }
        
        return $buffer;
    }
    
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof Address;
    }
}
