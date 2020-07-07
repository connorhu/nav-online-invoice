<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request\Software;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class SoftwareNormalizer implements ContextAwareNormalizerInterface
{
    public function normalize($software, $format = null, array $context = [])
    {
        $buffer = [
            'softwareId' => $software->getId(),
            'softwareName' => $software->getName(),
            'softwareOperation' => $software->getOperation(),
            'softwareMainVersion' => $software->getMainVersion(),
            'softwareDevName' => $software->getDevName(),
            'softwareDevContact' => $software->getDevContact(),
        ];
        
        if ($software->getDevCountryCode()) {
            $buffer['softwareDevCountryCode'] = $software->getDevCountryCode();
        }
        
        if ($software->getDevTaxNumber()) {
            $buffer['softwareDevTaxNumber'] = $software->getDevTaxNumber();
        }
        
        return $buffer;
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Software;
    }
}
