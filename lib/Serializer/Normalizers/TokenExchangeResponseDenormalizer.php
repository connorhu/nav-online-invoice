<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Response\TokenExchangeResponse;
use NAV\OnlineInvoice\Providers\CryptoToolsProviderInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

class TokenExchangeResponseDenormalizer implements ContextAwareDenormalizerInterface, SerializerAwareInterface
{
    private $serializer;
    
    private $cryptoTools;
    
    public function __construct(CryptoToolsProviderInterface $cryptoTools)
    {
        $this->cryptoTools = $cryptoTools;
    }
    
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        $exchangeToken = $this->cryptoTools->decodeExchangeToken($data['encodedExchangeToken']);

        $response = new TokenExchangeResponse();
        $response->setExchangeToken($exchangeToken);
        $response->setValidFrom(new \DateTime($data['tokenValidityFrom']));
        $response->setValidTo(new \DateTime($data['tokenValidityTo']));
        
        return $response;
    }

    public function supportsDenormalization($data, string $type, ?string $format = null, array $context = [])
    {
        return $type === TokenExchangeResponse::class;
    }
}
