<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request\QueryTaxpayerRequest;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class QueryTaxpayerRequestNormalizer implements ContextAwareNormalizerInterface
{
    private RequestNormalizer $requestNormalizer;
    
    public function __construct(RequestNormalizer $requestNormalizer)
    {
        $this->requestNormalizer = $requestNormalizer;
    }
    
    public function normalize($object, string $format = null, array $context = [])
    {
        $buffer = [
            'taxNumber' => $object->getTaxNumber(),
        ];

        return $this->requestNormalizer->normalize($object, $format, [
            RequestNormalizer::REQUEST_CONTENT_KEY => $buffer
        ]);
    }
    
    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof QueryTaxpayerRequest;
    }
}
