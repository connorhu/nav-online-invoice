<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\Header;
use NAV\OnlineInvoice\Http\Request\ManageInvoiceRequest;
use NAV\OnlineInvoice\Providers\CryptoToolsProviderInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;

class ManageInvoiceRequestNormalizer implements ContextAwareNormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    private CryptoToolsProviderInterface $cryptoTools;

    private RequestNormalizer $requestNormalizer;

    /**
     * @var InvoiceNormalizer
     */
    private InvoiceNormalizer $invoiceNormalizer;

    public function __construct(RequestNormalizer $requestNormalizer, CryptoToolsProviderInterface $cryptoTools)
    {
        $this->requestNormalizer = $requestNormalizer;
        $this->cryptoTools = $cryptoTools;
    }

    protected function normalizeV20($object, $format = null, array $context = []): array
    {
        $buffer = [];
        $buffer['exchangeToken'] = $object->getExchangeToken();
        $buffer['invoiceOperations'] = [
            'compressedContent' => RequestNormalizer::normalizeBool($object->isContentCompressed()),
            'invoiceOperation' => [],
        ];

        if ($object->getHeader()->getRequestVersion() !== Header::REQUEST_VERSION_V20) {
            throw new \Exception('request version not supported: '. $object->getHeader()->getRequestVersion());
        }

        $operations = array_values($object->getInvoiceOperations());
        foreach ($operations as $index => $invoiceOperation) {
            $serializedInvoice = $this->serializer->serialize($invoiceOperation->getInvoice(), $format, [
                'xml_root_node_name' => 'InvoiceData',
                'request_version' => $object->getRequestVersion(),
            ]);

            $encodedInvoiceData = $this->cryptoTools->encodeInvoiceData($serializedInvoice);

            $buffer['invoiceOperations']['invoiceOperation'][] = [
                'index' => $index + 1,
                'invoiceOperation' => $invoiceOperation->getOperation(),
                'invoiceData' => $encodedInvoiceData,
            ];
        }

        return $buffer;
    }

    protected function normalizeV30($object, $format = null, array $context = []): array
    {
        $contentToSign = [
            'exchangeToken' => $object->getExchangeToken(),
            'invoiceOperations' => [
                'compressedContent' => $object->isContentCompressed(),
                'invoiceOperation' => []
            ],
        ];

        $operations = array_values($object->getInvoiceOperations());
        foreach ($operations as $index => $invoiceOperation) {
            $serializedInvoice = $this->serializer->serialize($invoiceOperation->getInvoice(), 'invoice_xml', [
                'request_version' => $object->getRequestVersion(),
            ]);

            $encodedInvoiceData = $this->cryptoTools->encodeInvoiceData($serializedInvoice);

            $contentToSign['invoiceOperations']['invoiceOperation'][] = [
                'index' => $index + 1,
                'invoiceOperation' => $invoiceOperation->getOperation(),
                'invoiceData' => $encodedInvoiceData,
            ];
        }

        return $this->requestNormalizer->normalize($object, $format, [
            RequestNormalizer::REQUEST_CONTENT_KEY => $contentToSign,
        ]);
    }

    /**
     * @param Request $object
     * @param null $format
     * @param array $context
     * @return array
     * @throws \Exception
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        if ($object->getRequestVersion() === Request::REQUEST_VERSION_V20) {
            return $this->normalizeV20($object, $format, $context);
        }
        elseif ($object->getRequestVersion() === Request::REQUEST_VERSION_V30) {
            return $this->normalizeV30($object, $format, $context);
        }
    }
    
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof ManageInvoiceRequest;
    }
}
