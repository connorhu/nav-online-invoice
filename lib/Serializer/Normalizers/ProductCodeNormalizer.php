<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Model\ProductCode;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class ProductCodeNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    public function normalize($object, string $format = null, array $context = []): array
    {
        $buffer = [];
        
        $buffer['productCodeCategory'] = $object->getProductCodeCategory();
        $buffer['productCodeValue'] = $object->getProductCodeValue();
    
        if ($object->getProductCodeCategory() === ProductCode::PRODUCT_CODE_CATEGORY_OWN) {
            $buffer['productCodeOwnValue'] = $object->getProductCodeOwnValue();
        }
    
        return ['productCode' => $buffer];
    }
    
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof ProductCode;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            ProductCode::class => true,
        ];
    }
}
