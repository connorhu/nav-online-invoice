<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\Header;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class HeaderNormalizer implements NormalizerInterface
{
    public function normalize($header, $format = null, array $context = []): array
    {
        if (in_array($header->getRequest()->getRequestVersion(), [Request::REQUEST_VERSION_V10, Request::REQUEST_VERSION_V11, Request::REQUEST_VERSION_V20])) {
            $namespace = '';
        }
        else {
            $namespace = 'common:';
        }
        
        return [
            $namespace.'requestId' => $header->getRequest()->getRequestId(),
            $namespace.'timestamp' => $header->getTimestamp()->format('Y-m-d\TH:i:s.000\Z'),
            $namespace.'requestVersion' => $header->getRequest()->getRequestVersion(),
            $namespace.'headerVersion' => $header->getHeaderVersion(),
        ];
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof Header;
    }
}
