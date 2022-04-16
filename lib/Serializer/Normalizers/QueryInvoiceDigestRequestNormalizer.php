<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request\QueryInvoiceDigestRequest;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

class QueryInvoiceDigestRequestNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
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
            'page' => $object->getPage(),
            'invoiceDirection' => $object->getInvoiceDirection(),
            'invoiceQueryParams' => [],
        ];

        if (($from = $object->getIssueDateFrom()) !== null) {
            $buffer['invoiceQueryParams']['mandatoryQueryParams']['invoiceIssueDate']['dateFrom'] = $this->normalizer->normalize($from, null, [
                DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'
            ]);
        }

        if (($to = $object->getIssueDateTo()) !== null) {
            $buffer['invoiceQueryParams']['mandatoryQueryParams']['invoiceIssueDate']['dateTo'] = $this->normalizer->normalize($to, null, [
                DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'
            ]);
        }

        if (($taxNumber = $object->getTaxNumber()) !== null) {
            $buffer['invoiceQueryParams']['additionalQueryParams']['taxNumber'] = $taxNumber;
        }

        return $this->requestNormalizer->normalize($object, $format, [
            RequestNormalizer::REQUEST_CONTENT_KEY => $buffer
        ]);
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof QueryInvoiceDigestRequest;
    }
}
