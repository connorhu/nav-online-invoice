<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Enums\RequestVersionEnum;
use NAV\OnlineInvoice\Http\Request\ManageInvoiceRequest;
use NAV\OnlineInvoice\Logger\InvoiceLoggerInterface;
use NAV\OnlineInvoice\Logger\NullInvoiceLogger;
use NAV\OnlineInvoice\Providers\CryptoToolsProviderInterface;
use NAV\OnlineInvoice\Validator\Exceptions\InvalidXMLException;
use NAV\OnlineInvoice\Validator\XSDValidator;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class ManageInvoiceRequestNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    /**
     * @var InvoiceNormalizer
     */
    private InvoiceNormalizer $invoiceNormalizer;

    private XSDValidator $xsdValidator;

    private InvoiceLoggerInterface $invoiceLogger;

    public function __construct(
        private readonly RequestNormalizer $requestNormalizer,
        private readonly CryptoToolsProviderInterface $cryptoTools,
        ?InvoiceLoggerInterface $invoiceLogger = null,
    ) {
        $this->xsdValidator = new XSDValidator(__DIR__.'/../../Resources/XSD/catalog.xsd');
        $this->invoiceLogger = $invoiceLogger ?? new NullInvoiceLogger();
    }

    protected function normalizeV20(ManageInvoiceRequest $object, $format = null, array $context = []): array
    {
        $buffer = [];
        $buffer['exchangeToken'] = $object->getExchangeToken();
        $buffer['invoiceOperations'] = [
            'compressedContent' => RequestNormalizer::normalizeBool($object->isContentCompressed()),
            'invoiceOperation' => [],
        ];

        if (($requestVersion = $object->getHeader()->getRequestVersion()) !== RequestVersionEnum::v20) {
            throw new \Exception('request version not supported: '. $requestVersion);
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
                'invoiceOperation' => $invoiceOperation->getOperation()->value,
                'invoiceData' => $encodedInvoiceData,
            ];
        }

        return $buffer;
    }

    protected function normalizeV30(ManageInvoiceRequest $object, $format = null, array $context = []): array
    {
        $contentToSign = [
            'exchangeToken' => $object->getExchangeToken(),
            'invoiceOperations' => [
                'compressedContent' => BooleanNormalizer::normalize($object->isContentCompressed()),
                'invoiceOperation' => []
            ],
        ];

        $operations = array_values($object->getInvoiceOperations());
        foreach ($operations as $index => $invoiceOperation) {
            $serializedInvoice = $this->serializer->serialize($invoiceOperation->getInvoice(), 'invoice_xml', [
                'request_version' => $object->getRequestVersion(),
            ]);

            $this->xsdValidator->validate($serializedInvoice);

            if (count($errors = $this->xsdValidator->getErrors()) > 0) {
                throw new InvalidXMLException($errors, $this->xsdValidator->getFormattedXml());
            }

            $this->invoiceLogger->logInvoice($invoiceOperation->getInvoice(), $serializedInvoice);

            $encodedInvoiceData = $this->cryptoTools->encodeInvoiceData($serializedInvoice);

            $contentToSign['invoiceOperations']['invoiceOperation'][] = [
                'index' => $index + 1,
                'invoiceOperation' => $invoiceOperation->getOperation()->value,
                'invoiceData' => $encodedInvoiceData,
            ];
        }

        return $this->requestNormalizer->normalize($object, $format, [
            RequestNormalizer::REQUEST_CONTENT_KEY => $contentToSign,
        ]);
    }

    /**
     * @param ManageInvoiceRequest $object
     * @param null $format
     * @param array $context
     * @return array
     * @throws \Exception
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        if ($object->getRequestVersion() === RequestVersionEnum::v20) {
            return $this->normalizeV20($object, $format, $context);
        } elseif ($object->getRequestVersion() === RequestVersionEnum::v30) {
            return $this->normalizeV30($object, $format, $context);
        }

        throw new \RuntimeException('version not supported: '. $object->getRequestVersion());
    }
    
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof ManageInvoiceRequest;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            ManageInvoiceRequest::class => true,
        ];
    }
}
