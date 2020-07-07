<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class ConstraintViolationListNormalizer implements ContextAwareNormalizerInterface
{
    private $normalizer;

    public function __construct(ConstraintViolationNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($violationList, $format = null, array $context = [])
    {
        $buffer = [];

        foreach ($violationList as $violation) {
            $buffer[] = $this->normalizer->normalize($violation, $format, $context);
        }
        
        return [
            'errors' => $buffer,
        ];
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof ConstraintViolationList;
    }
}
