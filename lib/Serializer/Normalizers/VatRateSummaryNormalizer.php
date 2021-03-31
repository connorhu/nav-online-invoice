<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Entity\VatRateSummary;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

class VatRateSummaryNormalizer implements ContextAwareNormalizerInterface, SerializerAwareInterface
{
    public static function normalizeVatRate($item, $format = null, array $context = [])
    {
        $buffer = [];
    
        if ($item->getVatRatePercentage()) {
            $buffer['vatPercentage'] = $item->getVatRatePercentage();
        }

        if ($item->getVatRateExemption()) {
            $excemption = strtolower($item->getVatRateExemption());
            if ($excemption === 'aam') {
                $buffer['vatExemption'] = [
                    'case' => 'AAM',
                    'reason' => 'Alanyi adómentes',
                ];
            }
            else {
                throw new \Exception('unimplemented vat rage exempltion');
            }
        }

        if ($item->getVatRateOutOfScope()) {
            throw new \Exception('unimplemented out of scope');
            $buffer['vatOutOfScope'] = $item->getVatRateOutOfScope();
        }

        if ($item->getVatRateDomesticReverseCharge()) {
            $buffer['vatDomesticReverseCharge'] = $item->getVatRateDomesticReverseCharge();
        }

        if ($item->getVatRateMarginSchemeVat()) {
            $buffer['marginSchemeVat'] = $item->getVatRateMarginSchemeVat();
        }

        if ($item->getVatRateMarginSchemeNoVat()) {
            $buffer['marginSchemeNoVat'] = $item->getVatRateMarginSchemeNoVat();
        }
    
        return $buffer;
    }
    
    public function normalize($invoice, $format = null, array $context = [])
    {
        $buffer = [];
        
        $buffer['vatRate'] = self::normalizeVatRate($invoice, $format, $context);
    
        $buffer['vatRateNetData'] = [
            'vatRateNetAmount' => $invoice->getNetAmount(),
            'vatRateNetAmountHUF' => $invoice->getNetAmountHUF(),
        ];

        $buffer['vatRateVatData'] = [
            'vatRateVatAmount' => $invoice->getVatAmount(),
            'vatRateVatAmountHUF' => $invoice->getVatAmountHUF(),
        ];


        $buffer['vatRateGrossData'] = [
            'vatRateGrossAmount' => $invoice->getGrossAmount(),
            'vatRateGrossAmountHUF' => $invoice->getGrossAmountHUF(),
        ];

        return $buffer;
    }
    
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof VatRateSummary;
    }
    
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
}
