<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request\Software;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SoftwareNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public const XMLNS_CONTEXT_KEY = '_software_xmlns';

    public function normalize($object, $format = null, array $context = []): array
    {
        $buffer = [
            'softwareId' => $object->getId(),
            'softwareName' => $object->getName(),
            'softwareOperation' => $object->getOperation(),
            'softwareMainVersion' => $object->getMainVersion(),
            'softwareDevName' => $object->getDevName(),
            'softwareDevContact' => $object->getDevContact(),
        ];
        
        if ($object->getDevCountryCode()) {
            $buffer['softwareDevCountryCode'] = $object->getDevCountryCode();
        }
        
        if ($object->getDevTaxNumber()) {
            $buffer['softwareDevTaxNumber'] = $object->getDevTaxNumber();
        }
        
        return $buffer;
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof Software;
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        if (!key_exists(self::XMLNS_CONTEXT_KEY, $context)) {
            // TODO create exception type
            throw new \RuntimeException('Context key missing: '. self::XMLNS_CONTEXT_KEY);
        }

        $object = new Software();

        $keyPrefix = !empty($context[self::XMLNS_CONTEXT_KEY]) ? $context[self::XMLNS_CONTEXT_KEY].':' : $context[self::XMLNS_CONTEXT_KEY];

        $object->setId($data[$keyPrefix.'softwareId']);
        $object->setName($data[$keyPrefix.'softwareName']);
        $object->setOperation($data[$keyPrefix.'softwareOperation']);
        $object->setMainVersion($data[$keyPrefix.'softwareMainVersion']);
        $object->setDevName($data[$keyPrefix.'softwareDevName']);
        $object->setDevContact($data[$keyPrefix.'softwareDevContact']);

        if (isset($data[$keyPrefix.'softwareDevCountryCode'])) {
            $object->setDevCountryCode($data[$keyPrefix.'softwareDevCountryCode']);
        }

        if (isset($data[$keyPrefix.'softwareDevTaxNumber'])) {
            $object->setDevTaxNumber($data[$keyPrefix.'softwareDevTaxNumber']);
        }

        return $object;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return Software::class === $type;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Software::class => true,
        ];
    }
}
