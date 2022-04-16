<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request\QueryInvoiceDataRequest;
use NAV\OnlineInvoice\Http\Request\QueryInvoiceDigestRequest;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

class QueryInvoiceDataRequestNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private RequestNormalizer $requestNormalizer;
    
    public function __construct(RequestNormalizer $requestNormalizer)
    {
        $this->requestNormalizer = $requestNormalizer;
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

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof QueryInvoiceDataRequest;
    }
}
