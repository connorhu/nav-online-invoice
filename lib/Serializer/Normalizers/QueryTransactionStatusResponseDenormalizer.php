<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Response\QueryTransactionStatusResponse;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class QueryTransactionStatusResponseDenormalizer implements ContextAwareDenormalizerInterface
{
    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        $response = new QueryTransactionStatusResponse();
        if (!isset($data['processingResults']['originalRequestVersion'])) {
            $response->setOriginalRequestVersion($data['processingResults']['originalRequestVersion']);
        }
        
        $processingResults = [];
        if (isset($data['processingResults']['processingResult']['index'])) {
            $processingResults = [$data['processingResults']['processingResult']];
        }
        elseif (isset($data['processingResults']['processingResult'][0])) {
            $processingResults = $data['processingResults']['processingResult'];
        }
        
        foreach ($processingResults as $processingResult) {
            $response->addProcessingResult($processingResult);
        }
        
        return $response;
    }

    public function supportsDenormalization($data, string $type, ?string $format = null, array $context = [])
    {
        return $type === QueryTransactionStatusResponse::class;
    }
}
