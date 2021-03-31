<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request\QueryTransactionStatusRequest;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

class QueryTransactionStatusRequestNormalizer implements ContextAwareNormalizerInterface, SerializerAwareInterface
{
    public function normalize($object, $format = null, array $context = [])
    {
        $buffer = [
            'transactionId' => $object->getTransactionId(),
        ];
        
        if ($object->getReturnOriginalRequest() === true) {
            $buffer['returnOriginalRequest'] = $object->getReturnOriginalRequest();
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
