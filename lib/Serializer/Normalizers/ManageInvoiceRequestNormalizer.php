<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request\Header;
use NAV\OnlineInvoice\Http\Request\ManageInvoiceRequest;
use NAV\OnlineInvoice\Providers\CryptoToolsProviderInterface;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ManageInvoiceRequestNormalizer implements ContextAwareNormalizerInterface, SerializerAwareInterface
{
    private $cryptoTools;
    
    public function __construct(CryptoToolsProviderInterface $cryptoTools)
    {
        $this->cryptoTools = $cryptoTools;
    }

    public function normalize($request, $format = null, array $context = [])
    {
        $buffer = [];
        $buffer['exchangeToken'] = $request->getExchangeToken();
        $buffer['invoiceOperations'] = [
            'compressedContent' => RequestNormalizer::normalizeBool($request->isContentCompressed()),
            'invoiceOperation' => [],
        ];
        
        if ($request->getHeader()->getRequestVersion() !== Header::REQUEST_VERSION_V20) {
            throw new \Exception('request version not supported: '. $request->getHeader()->getRequestVersion());
        }

        $operations = array_values($request->getInvoiceOperations());
        foreach ($operations as $index => $invoiceOperation) {
            $serializedInvoice = $this->serializer->serialize($invoiceOperation->getInvoice(), $format, [
                'xml_root_node_name' => 'InvoiceData',
                'request_version' => $request->getHeader()->getRequestVersion(),
            ]);

            // dump($serializedInvoice);
            // exit;

            $encodedInvoiceData = $this->cryptoTools->encodeInvoiceData($serializedInvoice);
            
            $buffer['invoiceOperations']['invoiceOperation'][] = [
                'index' => $index + 1,
                'invoiceOperation' => $invoiceOperation->getOperation(),
                'invoiceData' => $encodedInvoiceData,
            ];
        }

        return $buffer;
    }
    
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        if (!isset($context['_in_request_normalizer']) || $context['_in_request_normalizer'] !== true) {
            return false;
        }
        
        return $data instanceof ManageInvoiceRequest;
    }
    
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
}
