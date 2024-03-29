<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Response;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class ResponseDenormalizer implements DenormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    public function denormalize($data, string $type, ?string $format = null, array $context = []): mixed
    {
        $inNormalizerContext = $context;
        $inNormalizerContext['_in_request_normalizer'] = true;
        return $this->serializer->denormalize($data, $type, $format, $inNormalizerContext);
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        if (isset($context['_in_response_denormalizer']) && $context['_in_response_denormalizer'] === true) {
            return false;
        }

        return $type === Response::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Response::class => true,
        ];
    }
}
