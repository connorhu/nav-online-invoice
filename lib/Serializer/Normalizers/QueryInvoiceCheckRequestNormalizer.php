<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request\QueryInvoiceCheckRequest;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class QueryInvoiceCheckRequestNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    public function __construct(private readonly RequestNormalizer $requestNormalizer)
    {
    }

    /**
     * @param QueryInvoiceCheckRequest $object
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        return $this->requestNormalizer->normalize($object, $format, [
            RequestNormalizer::REQUEST_CONTENT_KEY => InvoiceNumberQueryNormalizer::normalize($object),
        ]);
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof QueryInvoiceCheckRequest;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            QueryInvoiceCheckRequest::class => true,
        ];
    }
}
