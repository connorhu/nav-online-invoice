<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\ManageInvoiceRequest;
use NAV\OnlineInvoice\Http\Request\SignableContentInterface;
use NAV\OnlineInvoice\Providers\CryptoToolsProviderInterface;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

class RequestNormalizer implements ContextAwareNormalizerInterface, SerializerAwareInterface
{
    private $softwareNormalizer;
    private $serializer;
    private $cryptoTools;
    
    public function __construct(CryptoToolsProviderInterface $cryptoTools)
    {
        $this->cryptoTools = $cryptoTools;
    }
    
    public function normalize($request, $format = null, array $context = [])
    {
        $buffer = [];
        
        if ($format === 'xml') {
            $buffer['@xmlns'] = 'http://schemas.nav.gov.hu/OSA/2.0/api';
        }
        
        $buffer['header'] = [
            'requestId' => $request->getRequestId(),
            'timestamp' => $request->getHeader()->getTimestamp()->format('Y-m-d\TH:i:s.000\Z'),
            'requestVersion' => $request->getHeader()->getRequestVersion(),
            'headerVersion' => $request->getHeader()->getHeaderVersion(),
        ];
        
        $buffer['user'] = [
            'login' => $request->getUser()->getLogin(),
            'passwordHash' => $this->cryptoTools->getUserPasswordHash($request),
            'taxNumber' => $request->getUser()->getTaxNumber(),
        ];
        
        $buffer['software'] = $this->serializer->normalize($request->getSoftware(), $format, $context);
        
        $inNormalizerContext = $context;
        $inNormalizerContext['_in_request_normalizer'] = true;
        $normalizedContent = $this->serializer->normalize($request, $format, $inNormalizerContext);
        
        if ($request instanceof SignableContentInterface) {
            $signableContent = $request->normalizedContentToSignableContent($normalizedContent);
            $buffer['user']['requestSignature'] = $this->cryptoTools->signRequest($request, $signableContent);
        }
        else {
            $buffer['user']['requestSignature'] = $this->cryptoTools->signRequest($request);
        }

        return $buffer + $normalizedContent;
    }
    
    static public function normalizeBool(bool $value)
    {
        return $value === true ? 'true' : 'false';
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        if (isset($context['_in_request_normalizer']) && $context['_in_request_normalizer'] === true) {
            return false;
        }
        
        return $data instanceof Request;
    }
    
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
}
