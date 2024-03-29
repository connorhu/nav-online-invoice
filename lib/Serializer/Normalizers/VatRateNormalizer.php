<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Model\Interfaces\VatRateInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class VatRateNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function normalize($object, $format = null, array $context = []): array
    {
        $buffer = [];

        if ($object->getVatRatePercentage()) {
            $buffer['vatPercentage'] = $object->getVatRatePercentage();
        }

        if ($object->getVatRateExemptionCase()) {
            $buffer['vatExemption'] = [
                'case' => $object->getVatRateExemptionCaseString(),
                'reason' => $object->getVatRateExemptionReason(),
            ];
        }

        if ($object->getVatRateOutOfScopeCase()) {
            $buffer['vatOutOfScope'] = [
                'case' => $object->getVatRateOutOfScopeCaseString(),
                'reason' => $object->getVatRateOutOfScopeReason()
            ];
        }

        if ($object->getVatRateDomesticReverseCharge()) {
            $buffer['vatDomesticReverseCharge'] = $object->getVatRateDomesticReverseCharge();
        }

        if ($object->getVatRateMarginSchemeVat()) {
            $buffer['marginSchemeVat'] = $object->getVatRateMarginSchemeVat();
        }

        if ($object->getVatRateMarginSchemeNoVat()) {
            $buffer['marginSchemeNoVat'] = $object->getVatRateMarginSchemeNoVat();
        }

        return $buffer;
    }

    public function supportsNormalization(mixed $data, string $format = null)
    {
        return $data instanceof VatRateInterface;
    }

    public const ITEM_FACTORY_CONTEXT_KEY = '_vat_rate_key';
    public const XMLNS_CONTEXT_KEY = '_vat_rate_xmlns';

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
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

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return in_array(VatRateInterface::class, class_implements($type))
            || VatRateInterface::class === $type;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            VatRateInterface::class => true,
        ];
    }
}
