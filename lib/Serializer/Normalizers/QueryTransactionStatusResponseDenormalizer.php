<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Response\QueryTransactionStatusResponse;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class QueryTransactionStatusResponseDenormalizer implements ContextAwareDenormalizerInterface
{
    use ResponseDenormalizerTrait;

    protected function denormalizeV3($data): array
    {
        $namespace = self::getNamespaceWithUrl(ResponseDenormalizerInterface::API_SCHEMAS_URL_V30, $data);
        $apiKeyPrefix = $namespace !== '' ? $namespace.':' : $namespace;

        $namespace = self::getNamespaceWithUrl(ResponseDenormalizerInterface::COMMON_SCHEMAS_URL_V10, $data);
        $commonKeyPrefix = $namespace !== '' ? $namespace.':' : $namespace;

        $rawProcessingResults = $data[$apiKeyPrefix.'processingResults'];

        $response = new QueryTransactionStatusResponse();
        if (!isset($rawProcessingResults[$apiKeyPrefix.'originalRequestVersion'])) {
            $response->setOriginalRequestVersion($rawProcessingResults[$apiKeyPrefix.'originalRequestVersion']);
        }

        $processingResults = [];
        if (isset($rawProcessingResults[$apiKeyPrefix.'processingResult'][$apiKeyPrefix.'index'])) {
            $processingResults = [$rawProcessingResults['processingResult']];
        }
        elseif (isset($rawProcessingResults[$apiKeyPrefix.'processingResult'][0])) {
            $processingResults = $rawProcessingResults[$apiKeyPrefix.'processingResult'];
        }

        foreach ($processingResults as $processingResult) {
            $buffer = [
                'validationResultCode' => $processingResult[$commonKeyPrefix.'validationResultCode'],
                'validationErrorCode' => $processingResult[$commonKeyPrefix.'validationErrorCode'],
                'message' => $processingResult[$commonKeyPrefix.'message'],
            ];

            $response->addProcessingResult($buffer);
        }

        return $response;
    }

    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        $namespace = self::getNamespaceWithUrl(ResponseDenormalizerInterface::API_SCHEMAS_URL_V30, $data);

        if ($namespace !== null) { // v3
            return $this->denormalizeV3($data);
        }

        throw new \LogicException('Unknown response interface.');
    }

    public function supportsDenormalization($data, string $type, ?string $format = null, array $context = [])
    {
        return $type === QueryTransactionStatusResponse::class;
    }
}
