<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Model\Address;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class AddressNormalizer implements NormalizerInterface, SerializerAwareInterface
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
            return [
                'base:simpleAddress' => $addressData,
            ];
        }

        return [
            'base:detailedAddress' => $addressData,
        ];
    }
    
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof Address;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Address::class => true,
        ];
    }
}
