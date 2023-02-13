<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request\QueryTransactionStatusRequest;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class QueryTransactionStatusRequestNormalizer implements ContextAwareNormalizerInterface
{
    public function __construct(private readonly RequestNormalizer $requestNormalizer)
    {
    }

    public function normalize($object, $format = null, array $context = []): array
    {
        $buffer = [
            'transactionId' => $object->getTransactionId(),
        ];

        return $this->requestNormalizer->normalize($object, $format, [
            RequestNormalizer::REQUEST_CONTENT_KEY => $buffer
        ]);
    }
    
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof QueryTransactionStatusRequest;
    }
}
