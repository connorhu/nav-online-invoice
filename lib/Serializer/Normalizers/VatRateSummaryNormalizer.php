<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Entity\Interfaces\VatRateInterface;
use NAV\OnlineInvoice\Entity\VatRateSummary;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;

class VatRateSummaryNormalizer implements ContextAwareNormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    /**
     * @param VatRateInterface $item
     * @param $format
     * @param array $context
     * @return array
     */
    public static function normalizeVatRate($item, $format = null, array $context = [])
    {
        $buffer = [];
    
        if ($item->getVatRatePercentage()) {
            $buffer['vatPercentage'] = $item->getVatRatePercentage();
        }

        if ($item->getVatRateExemptionCase()) {
            $buffer['vatExemption'] = [
                'case' => $item->getVatRateExemptionCaseString(),
                'reason' => $item->getVatRateExemptionReason(),
            ];
        }

        if ($item->getVatRateOutOfScopeCase()) {
            $buffer['vatOutOfScope'] = [
                'case' => $item->getVatRateOutOfScopeCaseString(),
                'reason' => $item->getVatRateOutOfScopeReason()
            ];
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
}
