<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request\QueryTransactionStatusRequest;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

class QueryTransactionStatusRequestNormalizer implements ContextAwareNormalizerInterface, SerializerAwareInterface
{
    public function normalize($request, $format = null, array $context = [])
    {
        $buffer = [
            'transactionId' => $request->getTransactionId(),
        ];
        
        if ($request->getReturnOriginalRequest() === true) {
            $buffer['returnOriginalRequest'] = $request->getReturnOriginalRequest();
        }
        
        return $buffer;
    }
    
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        if (!isset($context['_in_request_normalizer']) || $context['_in_request_normalizer'] !== true) {
            return false;
        }
        
        return $data instanceof QueryTransactionStatusRequest;
    }
    
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
}
