<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Model\ProductCode;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class ProductCodeNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    public function normalize($code, string $format = null, array $context = [])
    {
        $buffer = [];
        
        $buffer['productCodeCategory'] = $code->getProductCodeCategory();
        $buffer['productCodeValue'] = $code->getProductCodeValue();
    
        if ($code->getProductCodeCategory() === ProductCode::PRODUCT_CODE_CATEGORY_OWN) {
            $buffer['productCodeOwnValue'] = $code->getProductCodeOwnValue();
        }
    
        return ['productCode' => $buffer];
    }
    
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof ProductCode;
    }
}
