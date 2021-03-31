<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Response;
use NAV\OnlineInvoice\Http\Response\QueryTaxpayerResponse;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;

class ResponseDenormalizer implements ContextAwareDenormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        $inNormalizerContext = $context;
        $inNormalizerContext['_in_request_normalizer'] = true;
        return $this->serializer->denormalize($data, $type, $format, $inNormalizerContext);
    }

    public function supportsDenormalization($data, string $type, ?string $format = null, array $context = [])
    {
        if (isset($context['_in_response_denormalizer']) && $context['_in_response_denormalizer'] === true) {
            return false;
        }

        return $type === Response::class;
    }
}
