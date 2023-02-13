<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\Header;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class HeaderNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public const XMLNS_CONTEXT_KEY = '_header_xmlns';

    public function normalize($object, $format = null, array $context = []): array
    {
        if (in_array($object->getRequest()->getRequestVersion(), [Request::REQUEST_VERSION_V10, Request::REQUEST_VERSION_V11, Request::REQUEST_VERSION_V20])) {
            $namespace = '';
        } else {
            $namespace = 'common:';
        }
        
        return [
            $namespace.'requestId' => $object->getRequest()->getRequestId(),
            $namespace.'timestamp' => $object->getTimestamp()->format('Y-m-d\TH:i:s.000\Z'),
            $namespace.'requestVersion' => $object->getRequest()->getRequestVersion(),
            $namespace.'headerVersion' => $object->getHeaderVersion(),
        ];
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof Header;
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        if (!key_exists(self::XMLNS_CONTEXT_KEY, $context)) {
            // TODO create exception type
            throw new \RuntimeException('Context key missing: '. self::XMLNS_CONTEXT_KEY);
        }

        $keyPrefix = empty($context[self::XMLNS_CONTEXT_KEY]) ? '' : ($context[self::XMLNS_CONTEXT_KEY].':');

        $object = new Header();
        $object->setHeaderVersion($data[$keyPrefix.'headerVersion']);
        $object->setTimestamp(new \DateTime($data[$keyPrefix.'timestamp']));

        return $object;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return $type === Header::class;
    }
}
