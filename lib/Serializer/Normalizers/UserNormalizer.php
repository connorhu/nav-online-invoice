<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Enums\RequestVersionEnum;
use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\User;
use NAV\OnlineInvoice\Providers\CryptoToolsProviderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserNormalizer implements NormalizerInterface
{
    public function __construct(private readonly CryptoToolsProviderInterface $cryptoTools)
    {
    }

    public function normalize($object, $format = null, array $context = []): array
    {
        if ($object->getRequest()->getRequestVersion() === RequestVersionEnum::v30) {
            return [
                'common:login' => $object->getLogin(),
                'common:passwordHash' => [
                    '@cryptoType' => $this->cryptoTools->getUserPasswordHashAlgo($object),
                    '#' => $this->cryptoTools->getUserPasswordHash($object),
                ],
                'common:taxNumber' => $object->getTaxNumber(),
            ];
        }

        $request = $object->getRequest();

        return [
            'login' => $object->getLogin(),
            'passwordHash' => $this->cryptoTools->signRequest($request),
            'taxNumber' => $object->getTaxNumber(),
        ];
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            User::class => true,
        ];
    }
}
