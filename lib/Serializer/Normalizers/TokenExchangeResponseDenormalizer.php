<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Response\TokenExchangeResponse;
use NAV\OnlineInvoice\Providers\CryptoToolsProviderInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class TokenExchangeResponseDenormalizer implements ContextAwareDenormalizerInterface
{
    use ResponseDenormalizerTrait;

    public function __construct(private readonly CryptoToolsProviderInterface $cryptoTools)
    {
    }

    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        $namespace = self::getNamespaceWithUrl(ResponseDenormalizerInterface::API_SCHEMAS_URL_V30, $data);

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
