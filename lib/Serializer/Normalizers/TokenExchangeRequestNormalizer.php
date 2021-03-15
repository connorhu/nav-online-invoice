<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request\TokenExchangeRequest;
use NAV\OnlineInvoice\Serializer\Normalizers\RequestNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class TokenExchangeRequestNormalizer implements ContextAwareNormalizerInterface
{
    private $requestNormalizer;
    
    public function __construct(RequestNormalizer $requestNormalizer)
    {
        $this->requestNormalizer = $requestNormalizer;
    }
    
    public function normalize($request, $format = null, array $context = [])
    {
        $data = $this->requestNormalizer->normalize($request, $format);
        return $data;
    }
    
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof TokenExchangeRequest;
    }

}
