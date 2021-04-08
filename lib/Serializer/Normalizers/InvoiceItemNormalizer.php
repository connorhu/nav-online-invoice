<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Entity\InvoiceItem;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

class InvoiceItemNormalizer implements ContextAwareNormalizerInterface, SerializerAwareInterface
{
    public function normalize($item, $format = null, array $context = [])
    {
        $buffer = [];
        
        $buffer['lineNumber'] = $item->getItemNumber();
        
        if ($item->getLineModificationReferenceNumber()) {
            $buffer['lineModificationReference'] = [
                'lineNumberReference' => $item->getLineModificationReferenceNumber(),
                'lineOperation' => $item->getLineModificationReferenceOperation(),
            ];
        }
    
        if ($item->getReferencesToOtherLines()) {
            foreach ($item->getReferencesToOtherLines() as $reference) {
                $buffer['referenceToOtherLines']['referenceToOtherLine'] = $reference;
            }
        }
        
        if ($item->getAdvanceIndicator()) {
            $buffer['advanceData'] = [
                'advanceIndicator' => $item->getAdvanceIndicator(),
            ];
        }
        
        foreach ($item->getProductCodes() as $code) {
            $buffer['productCodes'][] = $this->serializer->normalize($code, $format, $context);
        }
        
        $buffer['lineExpressionIndicator'] = $item->getLineExpressionIndicator() === true ? 'true' : 'false';
    
        if ($item->getLineDescription()) {
            $buffer['lineDescription'] = $item->getLineDescription();
        }
        
        if ($item->getQuantity()) {
            $buffer['quantity'] = $item->getQuantity();
        }

        if ($item->getUnitOfMeasure()) {
            $buffer['unitOfMeasure'] = $item->getUnitOfMeasure();
        }
        if ($item->getUnitOfMeasureOwn()) {
            $buffer['unitOfMeasureOwn'] = $item->getUnitOfMeasureOwn();
        }

        if ($item->getUnitPrice()) {
            $buffer['unitPrice'] = $item->getUnitPrice();
        }

        $buffer['lineAmountsNormal']['lineNetAmountData'] = [
            'lineNetAmount' => $item->getNetAmount(),
            'lineNetAmountHUF' => $item->getNetAmountHUF(),
        ];
        
        $buffer['lineAmountsNormal']['lineVatRate'] = VatRateSummaryNormalizer::normalizeVatRate($item, $format, $context);
        $buffer['lineAmountsNormal']['lineVatData'] = [
            'lineVatAmount' => $item->getVatAmount(),
            'lineVatAmountHUF' => $item->getVatAmountHUF(),
        ];

        if ($item->getGrossAmountNormal()) {
            $buffer['lineAmountsNormal']['lineGrossAmountData'] = [
                'lineGrossAmountNormal' => $item->getGrossAmountNormal(),
                'lineGrossAmountNormalHUF' => $item->getGrossAmountNormalHUF(),
            ];
        }

        if ($item->getIntermediatedService() !== null) {
            $buffer['intermediatedService'] = $item->getIntermediatedService();
        }
    
        foreach ($item->getAdditionalData() as $key => $data) {
            $buffer['additionalLineData'][] = [
                'dataName' => $key,
                'dataDescription' => $data['description'],
                'dataValue' => $data['value'],
            ];
        }
        
        return $buffer;
    }
    
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof InvoiceItem;
    }
    
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
}
