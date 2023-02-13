<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolation;

class ConstraintViolationNormalizer implements NormalizerInterface
{
    private function getBaseClassname(string $class)
    {
        return strtolower(substr($class, strrpos($class, '\\')+1));
    }
    
    public function normalize($violation, $format = null, array $context = []): array
    {
        return [
            'path' => $this->getBaseClassname(get_class($violation->getRoot())) .'.'. $violation->getPropertyPath(),
            'code' => $violation->getCode(),
            'message' => $violation->getMessage(),
        ];
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof ConstraintViolation;
    }
}
