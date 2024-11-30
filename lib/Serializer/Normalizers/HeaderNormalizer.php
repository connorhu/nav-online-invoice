<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Exceptions\InvalidArgumentException;
use NAV\OnlineInvoice\Http\Enums\HeaderVersionEnum;
use NAV\OnlineInvoice\Http\Request\Header;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class HeaderNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public const XMLNS_CONTEXT_KEY = '_header_xmlns';

    public function normalize($object, $format = null, array $context = []): array
    {
        if (!$object instanceof Header) {
            throw new InvalidArgumentException($object, Header::class);
        }

        if ($object->getRequest()->getRequestVersion()->toInt() >= 300) {
            $namespace = '';
        } else {
            $namespace = 'common:';
        }
        
        return [
            $namespace.'requestId' => $object->getRequest()->getRequestId(),
            $namespace.'timestamp' => $object->getTimestamp()->format('Y-m-d\TH:i:s.000\Z'),
            $namespace.'requestVersion' => $object->getRequest()->getRequestVersion(),
            $namespace.'headerVersion' => $object->getHeaderVersion()->value,
        ];
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof Header;
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        if (!key_exists(self::XMLNS_CONTEXT_KEY, $context)) {
            // TODO create exception type
            throw new \RuntimeException('Context key missing: '. self::XMLNS_CONTEXT_KEY);
        }

        $keyPrefix = empty($context[self::XMLNS_CONTEXT_KEY]) ? '' : ($context[self::XMLNS_CONTEXT_KEY].':');

        $object = new Header();
        $object->setHeaderVersion(HeaderVersionEnum::from($data[$keyPrefix.'headerVersion']));
        $object->setTimestamp(new \DateTimeImmutable($data[$keyPrefix.'timestamp']));

        return $object;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type === Header::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Header::class => true,
        ];
    }
}
