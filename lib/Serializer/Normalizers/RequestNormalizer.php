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

    public function normalize($object, string $format = null, array $context = []): array
    {
        $buffer = [];
        
        $commonNamespace = '';
        if ($format === 'request') {
            if ($object->getRequestVersion() === Request::REQUEST_VERSION_V30) {
                $buffer['@xmlns'] = 'http://schemas.nav.gov.hu/OSA/3.0/api';
                $buffer['@xmlns:common'] = 'http://schemas.nav.gov.hu/NTCA/1.0/common';
                
                $commonNamespace = 'common:';
            }
            
            $buffer['@root_node_name'] = $object::ROOT_NODE_NAME;
        }
        else {
            throw new \LogicException('Only request format supported');
        }
        
        if ($object instanceof HeaderAwareRequest) {
            $buffer[$commonNamespace.'header'] = $this->serializer->normalize($object->getHeader());
        }
        
        $buffer[$commonNamespace.'user'] = $this->serializer->normalize($object->getUser());
        
        if ($object instanceof SoftwareAwareRequest) {
            $buffer['software'] = $this->serializer->normalize($object->getSoftware(), $format, $context);
        }
        
        $normalizedRequestContent = [];
        if (!empty($context[self::REQUEST_CONTENT_KEY])) {
            $normalizedRequestContent = $context[self::REQUEST_CONTENT_KEY];
        }

        if ($object instanceof SignableContentInterface) {
            $contentToBeSign = $object->normalizedContentToSignableContent($normalizedRequestContent);
            $nodeText = $this->cryptoTools->signRequest($object, $contentToBeSign);
        }
        else {
            $nodeText = $this->cryptoTools->signRequest($object);
        }

        $buffer[$commonNamespace.'user'][$commonNamespace.'requestSignature'] = [
            '@cryptoType' => $this->cryptoTools->getRequestSignatureHashAlgo($object),
            '#' => $nodeText,
        ];

        return $buffer + $normalizedRequestContent;
    }

    /**
     * @param mixed $data
     * @param string|null $format
     * @param array $context
     * @return bool
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return false;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [];
    }
}
