<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Model\Enums\ProductCodeCategoryEnum;
use NAV\OnlineInvoice\Model\Interfaces\ProductCodeInterface;

class ProductCodeNormalizer
{
    public function normalize($object, string $format = null, array $context = []): array
    {
        $buffer = [];

        if (!($object instanceof ProductCodeInterface)) {
            throw new \LogicException('invalid object type');
        }
        
        $buffer['productCodeCategory'] = $object->getProductCodeCategory()->rawString();
        $buffer['productCodeValue'] = $object->getProductCodeValue();
    
        if ($object->getProductCodeCategory() === ProductCodeCategoryEnum::Own) {
            $buffer['productCodeOwnValue'] = $object->getProductCodeOwnValue();
        }
    
        return ['productCode' => $buffer];
    }
    
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof ProductCodeInterface;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            ProductCodeInterface::class => true,
        ];
    }
}
