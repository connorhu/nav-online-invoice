<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\User;
use NAV\OnlineInvoice\Providers\CryptoToolsProviderInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserNormalizer implements NormalizerInterface
{
    public function __construct(private readonly CryptoToolsProviderInterface $cryptoTools)
    {
    }

    public function normalize($user, $format = null, array $context = [])
    {
        if ($user->getRequest()->getRequestVersion() === Request::REQUEST_VERSION_V30) {
            return [
                'common:login' => $user->getLogin(),
                'common:passwordHash' => [
                    '@cryptoType' => $this->cryptoTools->getUserPasswordHashAlgo($user),
                    '#' => $this->cryptoTools->getUserPasswordHash($user),
                ],
                'common:taxNumber' => $user->getTaxNumber(),
            ];
        }

        $request = $user->getRequest();

        return [
            'login' => $user->getLogin(),
            'passwordHash' => $this->cryptoTools->signRequest($request),
            'taxNumber' => $user->getTaxNumber(),
        ];
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof User;
    }
}
