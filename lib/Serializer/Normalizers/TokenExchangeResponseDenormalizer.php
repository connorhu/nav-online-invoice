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

    const API_SCHEMAS_URL_V30 = 'http://schemas.nav.gov.hu/OSA/3.0/api';
    const DATA_SCHEMAS_URL_V30 = 'http://schemas.nav.gov.hu/OSA/3.0/data';
    const COMMON_SCHEMAS_URL_V10 = 'http://schemas.nav.gov.hu/NTCA/1.0/common';
    const BASE_SCHEMAS_URL_V30 = 'http://schemas.nav.gov.hu/OSA/3.0/base';

    protected static function getNamespaceWithUrl(string $url, array $data): ?string
    {
        foreach ($data as $key => $value) {
            if (substr($key, 0, 6) !== '@xmlns') {
                continue;
            }

            if ($value === $url) {
                return substr($key, 6);
            }
        }

        return null;
    }

    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        $namespace = self::getNamespaceWithUrl(self::API_SCHEMAS_URL_V30, $data);

        if ($namespace !== null) { // v3
            $keyPrefix = $namespace !== '' ? $namespace.':' : $namespace;

            $exchangeToken = $this->cryptoTools->decodeExchangeToken($data[$keyPrefix.'encodedExchangeToken']);

            $response = new TokenExchangeResponse();
            $response->setExchangeToken($exchangeToken);
            $response->setValidFrom(new \DateTime($data[$keyPrefix.'tokenValidityFrom']));
            $response->setValidTo(new \DateTime($data[$keyPrefix.'tokenValidityTo']));

            return $response;
        }
        
        throw new \LogicException('Unsupported response');
    }

    public function supportsDenormalization($data, string $type, ?string $format = null, array $context = [])
    {
        return $type === TokenExchangeResponse::class;
    }
}
