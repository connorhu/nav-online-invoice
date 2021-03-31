<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\HeaderAwareRequest;
use NAV\OnlineInvoice\Http\Request\SoftwareAwareRequest;
use NAV\OnlineInvoice\Http\Request\SignableContentInterface;
use NAV\OnlineInvoice\Providers\CryptoToolsProviderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class RequestNormalizer implements SerializerAwareInterface, NormalizerInterface
{
    use SerializerAwareTrait;
    
    public const REQUEST_CONTENT_KEY = '_request_content';

    /**
     * @var CryptoToolsProviderInterface
     */
    private CryptoToolsProviderInterface $cryptoTools;

    /**
     * RequestNormalizer constructor.
     * @param CryptoToolsProviderInterface $cryptoTools
     */
    public function __construct(CryptoToolsProviderInterface $cryptoTools)
    {
        $this->cryptoTools = $cryptoTools;
    }

    /**
     * @param mixed $request
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($request, string $format = null, array $context = [])
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
        
        if ($request instanceof HeaderAwareRequest) {
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

    /**
     * @param mixed $data
     * @param string|null $format
     * @return bool
     */
    public function supportsNormalization($data, string $format = null)
    {
        return false;
    }
}
