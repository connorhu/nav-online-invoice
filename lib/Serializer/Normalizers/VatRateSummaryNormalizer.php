<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Entity\Interfaces\VatRateInterface;
use NAV\OnlineInvoice\Entity\InvoiceItem;
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

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof VatRateSummary;
    }

    public const XMLNS_CONTEXT_KEY = '_invoice_item_xmlns';

    public static function denormalizeVatRate(InvoiceItem $item, array $data, $format = null, array $context = [])
    {
        if (!key_exists(self::XMLNS_CONTEXT_KEY, $context)) {
            // TODO create exception type
            throw new \RuntimeException('Context key missing: '. self::XMLNS_CONTEXT_KEY);
        }

        $keyPrefix = !empty($context[self::XMLNS_CONTEXT_KEY]) ? ($context[self::XMLNS_CONTEXT_KEY].':') : '';

        if (isset($data[$keyPrefix.'vatPercentage'])) {
            $item->setVatRatePercentage($data[$keyPrefix.'vatPercentage']);
        }

        if (isset($data[$keyPrefix.'vatExemption'])) {
            $item->setVatRateExemptionCaseWithString($data[$keyPrefix.'vatExemption'][$keyPrefix.'case']);
            $item->setVatRateExemptionReason($data[$keyPrefix.'vatExemption'][$keyPrefix.'reason']);
        }

        if (isset($data[$keyPrefix.'vatOutOfScope'])) {
            $item->setVatRateOutOfScopeCaseWithString($data[$keyPrefix.'vatOutOfScope'][$keyPrefix.'case']);
            $item->setVatRateOutOfScopeReason($data[$keyPrefix.'vatOutOfScope'][$keyPrefix.'reason']);
        }

        if (isset($data[$keyPrefix.'vatDomesticReverseCharge'])) {
            $item->setVatRateDomesticReverseCharge($data[$keyPrefix.'vatDomesticReverseCharge']);
        }

        if (isset($data[$keyPrefix.'marginSchemeVat'])) {
            $item->setVatRateMarginSchemeVat($data[$keyPrefix.'marginSchemeVat']);
        }

        if (isset($data[$keyPrefix.'marginSchemeNoVat'])) {
            $item->setVatRateMarginSchemeNoVat($data[$keyPrefix.'marginSchemeNoVat']);
        }
    }
}
