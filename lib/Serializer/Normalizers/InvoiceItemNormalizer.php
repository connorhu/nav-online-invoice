<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Model\Interfaces\InvoiceItemInterface;
use NAV\OnlineInvoice\Model\Interfaces\VatRateInterface;
use NAV\OnlineInvoice\Model\InvoiceItem;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class InvoiceItemNormalizer implements NormalizerInterface, NormalizerAwareInterface, DenormalizerInterface, DenormalizerAwareInterface
{
    use NormalizerAwareTrait;
    use DenormalizerAwareTrait;

    public function __construct(
        private readonly VatRateNormalizer $vatRateNormalizer,
    ) {
    }

    public function normalize($object, $format = null, array $context = []): array
    {
        $buffer = [];

        if (!$object instanceof InvoiceItemInterface) {
            throw new \RuntimeException('invalid type to normalize');
        }

        $buffer['lineNumber'] = $object->getItemNumber();

        if ($object->getLineModificationReferenceNumber()) {
            $buffer['lineModificationReference'] = [
                'lineNumberReference' => $object->getLineModificationReferenceNumber(),
                'lineOperation' => $object->getLineModificationReferenceOperation(),
            ];
        }

        if ($object->getReferencesToOtherLines()) {
            foreach ($object->getReferencesToOtherLines() as $reference) {
                $buffer['referenceToOtherLines']['referenceToOtherLine'] = $reference;
            }
        }

        if ($object->getAdvanceIndicator()) {
            $buffer['advanceData'] = [
                'advanceIndicator' => BooleanNormalizer::normalize($object->getAdvanceIndicator()),
            ];
        }

        if ($object->getAdvanceOriginalInvoice() !== null && $object->getAdvancePaymentDate() !== null && $object->getAdvanceExchangeRate() !== null) {
            $buffer['advanceData']['advancePaymentData'] = [
                'advanceOriginalInvoice' => $object->getAdvanceOriginalInvoice(),
                'advancePaymentDate' => $object->getAdvancePaymentDate()->format('Y-m-d'),
                'advanceExchangeRate' => $object->getAdvanceExchangeRate(),
            ];
        }

        foreach ($object->getProductCodes() as $code) {
            $buffer['productCodes'][] = $this->normalizer->normalize($code, $format, $context);
        }

        if ($object->getLineExpressionIndicator() !== null) {
            $buffer['lineExpressionIndicator'] = BooleanNormalizer::normalize($object->getLineExpressionIndicator());
        }

        if ($object->getLineDescription()) {
            $buffer['lineDescription'] = $object->getLineDescription();
        }

        if ($object->getQuantity()) {
            $buffer['quantity'] = $object->getQuantity();
        }

        if ($object->getUnitOfMeasure()) {
            $buffer['unitOfMeasure'] = $object->getUnitOfMeasure()->rawString();
        }
        if ($object->getUnitOfMeasureOwn()) {
            $buffer['unitOfMeasureOwn'] = $object->getUnitOfMeasureOwn();
        }

        if ($object->getUnitPrice()) {
            $buffer['unitPrice'] = $object->getUnitPrice();
        }

        $buffer['lineAmountsNormal']['lineNetAmountData'] = [
            'lineNetAmount' => $object->getNetAmount(),
            'lineNetAmountHUF' => $object->getNetAmountHUF(),
        ];

        $buffer['lineAmountsNormal']['lineVatRate'] = $this->vatRateNormalizer->normalize($object, $format, $context);
        $buffer['lineAmountsNormal']['lineVatData'] = [
            'lineVatAmount' => $object->getVatAmount(),
            'lineVatAmountHUF' => $object->getVatAmountHUF(),
        ];

        if ($object->getGrossAmountNormal()) {
            $buffer['lineAmountsNormal']['lineGrossAmountData'] = [
                'lineGrossAmountNormal' => $object->getGrossAmountNormal(),
                'lineGrossAmountNormalHUF' => $object->getGrossAmountNormalHUF(),
            ];
        }

        if ($object->getIntermediatedService() !== null) {
            $buffer['intermediatedService'] = $object->getIntermediatedService();
        }

        foreach ($object->getAdditionalItemData() as $key => $data) {
            $buffer['additionalLineData'][] = [
                'dataName' => $key,
                'dataDescription' => $data['description'],
                'dataValue' => $data['value'],
            ];
        }

        return $buffer;
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof InvoiceItemInterface;
    }

    public const XMLNS_CONTEXT_KEY = '_invoice_item_xmlns';

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        if (!key_exists(self::XMLNS_CONTEXT_KEY, $context)) {
            // TODO create exception type
            throw new \RuntimeException('Context key missing: '. self::XMLNS_CONTEXT_KEY);
        }

        $keyPrefix = !empty($context[self::XMLNS_CONTEXT_KEY]) ? ($context[self::XMLNS_CONTEXT_KEY].':') : '';

        $vatRateData = $data[$keyPrefix.'lineAmountsNormal'][$keyPrefix.'lineVatRate'];

        /** @var InvoiceItem $object */
        $object = $this->denormalizer->denormalize($vatRateData, VatRateInterface::class, $format, [
            VatRateNormalizer::XMLNS_CONTEXT_KEY => $context[self::XMLNS_CONTEXT_KEY],
            VatRateNormalizer::ITEM_FACTORY_CONTEXT_KEY => function () {
                return new InvoiceItem();
            }
        ]);

        $object->setItemNumber($data[$keyPrefix.'lineNumber']);
        $object->setLineExpressionIndicator($data[$keyPrefix.'lineExpressionIndicator']);
        $object->setLineDescription($data[$keyPrefix.'lineDescription']);
        $object->setQuantity($data[$keyPrefix.'quantity']);
        $object->setUnitOfMeasure($data[$keyPrefix.'unitOfMeasure']);
        $object->setUnitPrice($data[$keyPrefix.'unitPrice']);
        $object->setIntermediatedService(BooleanNormalizer::denormalize($data[$keyPrefix.'unitPrice']));

        $netAmountData = $data[$keyPrefix.'lineAmountsNormal'][$keyPrefix.'lineNetAmountData'];
        $object->setNetAmount($netAmountData[$keyPrefix.'lineNetAmount']);
        $object->setNetAmountHUF($netAmountData[$keyPrefix.'lineNetAmountHUF']);

        $vatAmountData = $data[$keyPrefix.'lineAmountsNormal'][$keyPrefix.'lineVatData'];
        $object->setVatAmount($vatAmountData[$keyPrefix.'lineVatAmount']);
        $object->setVatAmountHUF($vatAmountData[$keyPrefix.'lineVatAmountHUF']);

        if (isset($data[$keyPrefix.'lineAmountsNormal'][$keyPrefix.'lineGrossAmountData'][$keyPrefix.'lineGrossAmountNormal'])) {
            $grossAmountData = $data[$keyPrefix.'lineAmountsNormal'][$keyPrefix.'lineGrossAmountData'];
            $object->setGrossAmountNormal($grossAmountData[$keyPrefix.'lineGrossAmountNormal']);
            $object->setGrossAmountNormalHUF($grossAmountData[$keyPrefix.'lineGrossAmountNormalHUF']);
        }

        $lineData = [];
        if (isset($data[$keyPrefix.'additionalLineData']['dataName'])) {
            $lineData = [$data[$keyPrefix.'additionalLineData']];
        } elseif (isset($data[$keyPrefix.'additionalLineData'][0])) {
            $lineData = $data[$keyPrefix.'additionalLineData'];
        }
        foreach ($lineData as $additionalLineData) {
            $object->addAdditionalItemData($additionalLineData[$keyPrefix.'dataName'], $additionalLineData[$keyPrefix.'dataDescription'], $additionalLineData[$keyPrefix.'dataValue']);
        }

        return $object;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return InvoiceItem::class === $type;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            InvoiceItemInterface::class => true,
        ];
    }
}
