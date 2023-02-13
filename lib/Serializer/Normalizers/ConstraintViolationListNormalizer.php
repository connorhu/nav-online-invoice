<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class ConstraintViolationListNormalizer implements NormalizerInterface
{
    public function __construct(private readonly ConstraintViolationNormalizer $normalizer)
    {
    }

    public function normalize($violationList, $format = null, array $context = []): array
    {
        $buffer = [];

        foreach ($violationList as $violation) {
            $buffer[] = $this->normalizer->normalize($violation, $format, $context);
        }
        
        return [
            'errors' => $buffer,
        ];
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof ConstraintViolationList;
    }
}
