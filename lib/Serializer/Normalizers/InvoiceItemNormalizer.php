<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Entity\InvoiceItem;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;

class InvoiceItemNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    public function normalize($object, $format = null, array $context = []): array
    {
        $buffer = [];
        
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
                'advanceIndicator' => $object->getAdvanceIndicator(),
            ];
        }
        
        foreach ($object->getProductCodes() as $code) {
            $buffer['productCodes'][] = $this->serializer->normalize($code, $format, $context);
        }
        
        $buffer['lineExpressionIndicator'] = $object->getLineExpressionIndicator() === true ? 'true' : 'false';
    
        if ($object->getLineDescription()) {
            $buffer['lineDescription'] = $object->getLineDescription();
        }
        
        if ($object->getQuantity()) {
            $buffer['quantity'] = $object->getQuantity();
        }

        if ($object->getUnitOfMeasure()) {
            $buffer['unitOfMeasure'] = $object->getUnitOfMeasure();
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
        
        $buffer['lineAmountsNormal']['lineVatRate'] = VatRateSummaryNormalizer::normalizeVatRate($object, $format, $context);
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
    
        foreach ($object->getAdditionalData() as $key => $data) {
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
        return $data instanceof InvoiceItem;
    }
}
