<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request\TokenExchangeRequest;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

class TokenExchangeRequestNormalizer implements ContextAwareNormalizerInterface, SerializerAwareInterface
{
    public function normalize($request, $format = null, array $context = [])
    {
        return [];
    }
    
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        if (!isset($context['_in_request_normalizer']) || $context['_in_request_normalizer'] !== true) {
            return false;
        }
        
        return $data instanceof TokenExchangeRequest;
    }
    
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
}
