<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\HeaderAwareInterface;
use NAV\OnlineInvoice\Http\Request\SoftwareAwareRequest;
use NAV\OnlineInvoice\Http\Request\SignableContentInterface;
use NAV\OnlineInvoice\Providers\CryptoToolsProviderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;

class RequestNormalizer implements SerializerAwareInterface, NormalizerInterface
{
    use SerializerAwareTrait;
    
    public const REQUEST_CONTENT_KEY = '_request_content';

    private $cryptoTools;
    
    public function __construct(CryptoToolsProviderInterface $cryptoTools)
    {
        $this->cryptoTools = $cryptoTools;
    }
    
    public function normalize($request, $format = null, array $context = [])
    {
        $buffer = [];
        
        $commonNamespace = '';
        if ($format === 'request') {
            if ($request->getRequestVersion() === Request::REQUEST_VERSION_V30) {
                $buffer['@xmlns'] = 'http://schemas.nav.gov.hu/OSA/3.0/api';
                $buffer['@xmlns:common'] = 'http://schemas.nav.gov.hu/NTCA/1.0/common';
                
                $commonNamespace = 'common:';
            }
            
            $buffer['@root_node_name'] = $request::ROOT_NODE_NAME;
        }
        else {
            throw new \LogicException('Only request format supported');
        }
        
        if ($request instanceof HeaderAwareInterface) {
            $buffer[$commonNamespace.'header'] = $this->serializer->normalize($request->getHeader());
        }
        
        $buffer[$commonNamespace.'user'] = $this->serializer->normalize($request->getUser());
        
        if ($request instanceof SoftwareAwareRequest) {
            $buffer['software'] = $this->serializer->normalize($request->getSoftware(), $format, $context);
        }
        
        $normalizedRequestContent = [];
        if (!empty($context[self::REQUEST_CONTENT_KEY])) {
            $normalizedRequestContent = $context[self::REQUEST_CONTENT_KEY];
        }
        
        if ($request instanceof SignableContentInterface) {
            $signableContent = $request->normalizedContentToSignableContent($normalizedRequestContent);
            $buffer[$commonNamespace.'user']['common:requestSignature'] = $this->cryptoTools->signRequest($request, $signableContent);
        }
        else {
            $buffer[$commonNamespace.'user'][$commonNamespace.'requestSignature'] = [
                '@cryptoType' => $this->cryptoTools->getRequestSignatureHashAlgo($request),
                '#' => $this->cryptoTools->signRequest($request),
            ];
        }

        return $buffer + $normalizedRequestContent;
    }
    
    public function supportsNormalization($data, string $format = null)
    {
        return false;
    }
}
