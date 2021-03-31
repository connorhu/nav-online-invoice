<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Entity\ProductCode;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ProductCodeNormalizer implements ContextAwareNormalizerInterface, SerializerAwareInterface
{
    public function normalize($code, $format = null, array $context = [])
    {
        $buffer = [];
        
        $buffer['productCodeCategory'] = $code->getProductCodeCategory();
        $buffer['productCodeValue'] = $code->getProductCodeValue();
    
        if ($code->getProductCodeCategory() === self::PRODUCT_CODE_CATEGORY_OWN) {
            $buffer['productCodeOwnValue'] = $code->getProductCodeOwnValue();
        }
    
        return ['productCode' => $buffer];
    }
    
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof ProductCode;
    }
    
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
}
