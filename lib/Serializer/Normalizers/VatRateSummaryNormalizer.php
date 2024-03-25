<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Model\Interfaces\VatRateInterface;
use NAV\OnlineInvoice\Model\Interfaces\VatRateSummaryInterface;
use NAV\OnlineInvoice\Model\Invoice;
use NAV\OnlineInvoice\Model\InvoiceItem;
use NAV\OnlineInvoice\Model\VatRateSummary;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;

class VatRateSummaryNormalizer implements NormalizerInterface, DenormalizerInterface, NormalizerAwareInterface, DenormalizerAwareInterface
{
    use NormalizerAwareTrait;
    use DenormalizerAwareTrait;

    public function normalize($object, $format = null, array $context = [])
    {
        $buffer = [];

        $buffer['vatRate'] = $this->normalizer->normalize($object, $format, $context);

        $buffer['vatRateNetData'] = [
            'vatRateNetAmount' => $object->getNetAmount(),
            'vatRateNetAmountHUF' => $object->getNetAmountHUF(),
        ];

        $buffer['vatRateVatData'] = [
            'vatRateVatAmount' => $object->getVatAmount(),
            'vatRateVatAmountHUF' => $object->getVatAmountHUF(),
        ];


        $buffer['vatRateGrossData'] = [
            'vatRateGrossAmount' => $object->getGrossAmount(),
            'vatRateGrossAmountHUF' => $object->getGrossAmountHUF(),
        ];

        return $buffer;
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof VatRateSummary;
    }

    public const XMLNS_CONTEXT_KEY = '_vat_rate_xmlns';

    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        if (!key_exists(self::XMLNS_CONTEXT_KEY, $context)) {
            // TODO create exception type
            throw new \RuntimeException('Context key missing: '. self::XMLNS_CONTEXT_KEY);
        }

        $keyPrefix = !empty($context[self::XMLNS_CONTEXT_KEY]) ? ($context[self::XMLNS_CONTEXT_KEY].':') : '';

        $vatRateData = $data[$keyPrefix.'vatRate'];

        /** @var VatRateSummary $object */
        $object = $this->denormalizer->denormalize($vatRateData, VatRateInterface::class, $format, [
            VatRateNormalizer::XMLNS_CONTEXT_KEY => $context[self::XMLNS_CONTEXT_KEY],
            VatRateNormalizer::ITEM_FACTORY_CONTEXT_KEY => function () {
                return new VatRateSummary();
            }
        ]);

        $object->setNetAmount($data[$keyPrefix.'vatRateNetData'][$keyPrefix.'vatRateNetAmount']);
        $object->setNetAmountHUF($data[$keyPrefix.'vatRateNetData'][$keyPrefix.'vatRateNetAmountHUF']);
        $object->setVatAmount($data[$keyPrefix.'vatRateVatData'][$keyPrefix.'vatRateVatAmount']);
        $object->setVatAmountHUF($data[$keyPrefix.'vatRateVatData'][$keyPrefix.'vatRateVatAmount']);
        $object->setGrossAmount($data[$keyPrefix.'vatRateGrossData'][$keyPrefix.'vatRateGrossAmount']);
        $object->setGrossAmountHUF($data[$keyPrefix.'vatRateGrossData'][$keyPrefix.'vatRateGrossAmountHUF']);

        return $object;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null)
    {
        return $type === VatRateSummaryInterface::class;
    }
}
