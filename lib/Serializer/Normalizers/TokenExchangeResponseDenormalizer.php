<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Response\TokenExchangeResponse;
use NAV\OnlineInvoice\Providers\CryptoToolsProviderInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class TokenExchangeResponseDenormalizer implements ContextAwareDenormalizerInterface
{
    private $cryptoTools;
    
    public function __construct(CryptoToolsProviderInterface $cryptoTools)
    {
        $this->cryptoTools = $cryptoTools;
    }
    
    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        foreach ($data as $key => $value) {
            if (substr($key, 0, 6) !== '@xmlns') {
                continue;
            }
            
            if ($value === 'http://schemas.nav.gov.hu/OSA/3.0/api') {
                $responseVersion = 'v3.0';
                $namespaceName = substr($key, 7);
                if ($namespaceName === false) {
                    $namespaceName = '';
                }
                else {
                    $namespaceName .= ':';
                }
                break;
            }
        }
        
        if ($responseVersion === 'v3.0') {
            $exchangeToken = $this->cryptoTools->decodeExchangeToken($data[$namespaceName.'encodedExchangeToken']);

            $response = new TokenExchangeResponse();
            $response->setExchangeToken($exchangeToken);
            $response->setValidFrom(new \DateTime($data[$namespaceName.'tokenValidityFrom']));
            $response->setValidTo(new \DateTime($data[$namespaceName.'tokenValidityTo']));
            
            return $response;
        }
        
        throw new \LogicException('Unsupported namespace');
    }

    public function supportsDenormalization($data, string $type, ?string $format = null, array $context = [])
    {
        return $type === TokenExchangeResponse::class;
    }
}
