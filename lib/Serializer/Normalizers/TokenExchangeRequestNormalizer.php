<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request\TokenExchangeRequest;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TokenExchangeRequestNormalizer implements NormalizerInterface
{
    private RequestNormalizer $requestNormalizer;
    
    public function __construct(RequestNormalizer $requestNormalizer)
    {
        $this->requestNormalizer = $requestNormalizer;
    }
    
    public function normalize($object, $format = null, array $context = []): array
    {
        return $this->requestNormalizer->normalize($object, $format);
    }
    
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof TokenExchangeRequest;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            TokenExchangeRequest::class => true,
        ];
    }
}
