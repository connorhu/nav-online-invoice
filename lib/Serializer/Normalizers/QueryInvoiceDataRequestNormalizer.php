<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request\QueryInvoiceDataRequest;
use NAV\OnlineInvoice\Http\Request\QueryInvoiceDigestRequest;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class QueryInvoiceDataRequestNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    public function __construct(private readonly RequestNormalizer $requestNormalizer)
    {
    }

    /**
     * @param QueryInvoiceDigestRequest $object
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $buffer = [
            'invoiceNumberQuery' => [
                'invoiceNumber' => $object->getInvoiceNumber(),
                'invoiceDirection' => $object->getInvoiceDirection(),
            ],
        ];

        return $this->requestNormalizer->normalize($object, $format, [
            RequestNormalizer::REQUEST_CONTENT_KEY => $buffer
        ]);
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof QueryInvoiceDataRequest;
    }
}
