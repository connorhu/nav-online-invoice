<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Factories\VatRateFactoryInterface;
use NAV\OnlineInvoice\Model\Enums\VatRateAmountMismatchCase;
use NAV\OnlineInvoice\Model\Enums\VatRateExemptionCase;
use NAV\OnlineInvoice\Model\Enums\VatRateOutOfScopeCase;
use NAV\OnlineInvoice\Model\Interfaces\VatRateInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class VatRateNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function __construct(
        private readonly VatRateFactoryInterface $vatRateFactory
    ) {
    }

    public function normalize($object, $format = null, array $context = []): array
    {
        $buffer = [];

        if (!$object instanceof VatRateInterface) {
            exit;
        }

        if ($object->getVatRatePercentage() !== null) {
            $buffer['vatPercentage'] = $object->getVatRatePercentage();
        }

        if ($object->getVatRateContent() !== null) {
            $buffer['vatContent'] = $object->getVatRateContent();
        }

        if ($object->getVatRateExemptionCase() !== null) {
            $buffer['vatExemption'] = [
                'case' => $object->getVatRateExemptionCase()->toString(),
            ];

            if ($object->getVatRateExemptionReason() !== null) {
                $buffer['vatExemption']['reason'] = $object->getVatRateExemptionReason();
            }
        }

        if ($object->getVatRateOutOfScopeCase() !== null) {
            $buffer['vatOutOfScope'] = [
                'case' => $object->getVatRateOutOfScopeCase()->toString(),
            ];

            if ($object->getVatRateOutOfScopeReason() !== null) {
                $buffer['vatOutOfScope']['reason'] = $object->getVatRateOutOfScopeReason();
            }
        }

        if ($object->getVatRateDomesticReverseCharge() !== null) {
            $buffer['vatDomesticReverseCharge'] = $object->getVatRateDomesticReverseCharge();
        }

        if ($object->getVatRateMarginSchemeIndicator() !== null) {
            $buffer['marginSchemeIndicator'] = $object->getVatRateMarginSchemeIndicator();
        }

        if ($object->getVatRateAmountMismatchCase() !== null && $object->getVatRateAmountMismatchRate() !== null) {
            $buffer['vatAmountMismatch'] = [
                'vatRate' => $object->getVatRateAmountMismatchRate(),
                'case' => $object->getVatRateAmountMismatchCase()->toString(),
            ];
        }

        if ($object->getVatRateNoVatCharge() !== null) {
            $buffer['noVatCharge'] = $object->getVatRateNoVatCharge();
        }

        return $buffer;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof VatRateInterface;
    }

    public const XMLNS_CONTEXT_KEY = '_vat_rate_xmlns';

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        if (!key_exists(self::XMLNS_CONTEXT_KEY, $context)) {
            // TODO create exception type
            throw new \RuntimeException(sprintf('Context key missing: "%s"', self::XMLNS_CONTEXT_KEY));
        }

        $keyPrefix = !empty($context[self::XMLNS_CONTEXT_KEY]) ? ($context[self::XMLNS_CONTEXT_KEY].':') : '';

        $item = $this->vatRateFactory->createVatRate();

        if (isset($data[$keyPrefix.'vatPercentage'])) {
            $item->setVatRatePercentage($data[$keyPrefix.'vatPercentage']);
        }

        if (isset($data[$keyPrefix.'vatContent'])) {
            $item->setVatRateContent($data[$keyPrefix.'vatContent']);
        }

        if (isset($data[$keyPrefix.'vatExemption'])) {
            $item->setVatRateExemptionCase(VatRateExemptionCase::initWithString($data[$keyPrefix.'vatExemption'][$keyPrefix.'case']));
            $item->setVatRateExemptionReason($data[$keyPrefix.'vatExemption'][$keyPrefix.'reason']);
        }

        if (isset($data[$keyPrefix.'vatOutOfScope'])) {
            $item->setVatRateOutOfScopeCase(VatRateOutOfScopeCase::initWithString($data[$keyPrefix.'vatOutOfScope'][$keyPrefix.'case']));
            $item->setVatRateOutOfScopeReason($data[$keyPrefix.'vatOutOfScope'][$keyPrefix.'reason']);
        }

        if (isset($data[$keyPrefix.'vatDomesticReverseCharge'])) {
            $item->setVatRateDomesticReverseCharge($data[$keyPrefix.'vatDomesticReverseCharge']);
        }

        if (isset($data[$keyPrefix.'marginSchemeIndicator'])) {
            $item->setVatRateMarginSchemeIndicator($data[$keyPrefix.'marginSchemeIndicator']);
        }

        if (isset($data[$keyPrefix.'vatAmountMismatch'])) {
            $item->setVatRateAmountMismatchCase(VatRateAmountMismatchCase::initWithString($data[$keyPrefix.'vatAmountMismatch'][$keyPrefix.'case']));
            $item->setVatRateAmountMismatchRate($data[$keyPrefix.'vatAmountMismatch'][$keyPrefix.'vatRate']);
        }

        if (isset($data[$keyPrefix.'noVatCharge'])) {
            $item->setVatRateNoVatCharge($data[$keyPrefix.'noVatCharge']);
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
