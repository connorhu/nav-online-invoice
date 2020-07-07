<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Validator\ConstraintViolation;

class ConstraintViolationNormalizer implements ContextAwareNormalizerInterface
{
    private function getBaseClassname(string $class)
    {
        return strtolower(substr($class, strrpos($class, '\\')+1));
    }
    
    public function normalize($violation, $format = null, array $context = [])
    {
        return [
            'path' => $this->getBaseClassname(get_class($violation->getRoot())) .'.'. $violation->getPropertyPath(),
            'code' => $violation->getCode(),
            'message' => $violation->getMessage(),
        ];
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof ConstraintViolation;
    }
}
