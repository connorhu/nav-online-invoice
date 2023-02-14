<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Entity\Interfaces\VatRateInterface;
use NAV\OnlineInvoice\Entity\Interfaces\VatRateSummaryInterface;
use NAV\OnlineInvoice\Entity\InvoiceItem;
use NAV\OnlineInvoice\Entity\VatRateSummary;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;

class VatRateNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function normalize($data, $format = null, array $context = [])
    {
        $buffer = [];

        if ($data->getVatRatePercentage()) {
            $buffer['vatPercentage'] = $data->getVatRatePercentage();
        }

        if ($data->getVatRateExemptionCase()) {
            $buffer['vatExemption'] = [
                'case' => $data->getVatRateExemptionCaseString(),
                'reason' => $data->getVatRateExemptionReason(),
            ];
        }

        if ($data->getVatRateOutOfScopeCase()) {
            $buffer['vatOutOfScope'] = [
                'case' => $data->getVatRateOutOfScopeCaseString(),
                'reason' => $data->getVatRateOutOfScopeReason()
            ];
        }

        if ($data->getVatRateDomesticReverseCharge()) {
            $buffer['vatDomesticReverseCharge'] = $data->getVatRateDomesticReverseCharge();
        }

        if ($data->getVatRateMarginSchemeVat()) {
            $buffer['marginSchemeVat'] = $data->getVatRateMarginSchemeVat();
        }

        if ($data->getVatRateMarginSchemeNoVat()) {
            $buffer['marginSchemeNoVat'] = $data->getVatRateMarginSchemeNoVat();
        }

        return $buffer;
    }

    public function supportsNormalization(mixed $data, string $format = null)
    {
        return $data instanceof VatRateInterface;
    }

    public const ITEM_FACTORY_CONTEXT_KEY = '_vat_rate_key';
    public const XMLNS_CONTEXT_KEY = '_vat_rate_xmlns';

    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        if (!key_exists(self::XMLNS_CONTEXT_KEY, $context)) {
            // TODO create exception type
            throw new \RuntimeException('Context key missing: '. self::XMLNS_CONTEXT_KEY);
        }

        if (!key_exists(self::ITEM_FACTORY_CONTEXT_KEY, $context)) {
            // TODO create exception type
            throw new \RuntimeException('Context key missing: '. self::ITEM_FACTORY_CONTEXT_KEY);
        }

        $itemFactory = $context[self::ITEM_FACTORY_CONTEXT_KEY];
        $keyPrefix = !empty($context[self::XMLNS_CONTEXT_KEY]) ? ($context[self::XMLNS_CONTEXT_KEY].':') : '';

        /** @var VatRateInterface $item */
        $item = $itemFactory();

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

        return $item;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null)
    {
        return in_array(VatRateInterface::class, class_implements($type));
    }
}
