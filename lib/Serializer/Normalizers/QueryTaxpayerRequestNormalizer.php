<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request\QueryTaxpayerRequest;
use NAV\OnlineInvoice\Serializer\Normalizers\RequestNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

class QueryTaxpayerRequestNormalizer implements ContextAwareNormalizerInterface
{
    private $requestNormalizer;
    
    public function __construct(RequestNormalizer $requestNormalizer)
    {
        $this->requestNormalizer = $requestNormalizer;
    }
    
    public function normalize($request, $format = null, array $context = [])
    {
        $buffer = [
            'taxNumber' => $request->getTaxNumber(),
        ];

        return $this->requestNormalizer->normalize($request, $format, [
            RequestNormalizer::REQUEST_CONTENT_KEY => $buffer
        ]);
    }
    
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof QueryTaxpayerRequest;
    }
}
