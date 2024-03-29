<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolation;

class ConstraintViolationNormalizer implements NormalizerInterface
{
    private function getBaseClassname(string $class): string
    {
        return strtolower(substr($class, strrpos($class, '\\')+1));
    }
    
    public function normalize($object, $format = null, array $context = []): array
    {
        return [
            'path' => $this->getBaseClassname(get_class($object->getRoot())) .'.'. $object->getPropertyPath(),
            'code' => $object->getCode(),
            'message' => $object->getMessage(),
        ];
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof ConstraintViolation;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            ConstraintViolation::class => true,
        ];
    }
}
