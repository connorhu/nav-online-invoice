<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Response\ManageInvoiceResponse;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ManageInvoiceResponseDenormalizer implements ContextAwareDenormalizerInterface, SerializerAwareInterface
{
    private $serializer;
    
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        $response = new ManageInvoiceResponse();
        
        $response->setTransactionId($data['transactionId']);
        
        return $response;
    }

    public function supportsDenormalization($data, string $type, ?string $format = null, array $context = [])
    {
        return $type === ManageInvoiceResponse::class;
    }
}
