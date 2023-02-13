<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Response\QueryTransactionStatusResponse;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class QueryTransactionStatusResponseDenormalizer implements DenormalizerInterface
{
    use ResponseDenormalizerTrait;

    protected function denormalizeV3($data): QueryTransactionStatusResponse
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
            $processingResults = [$rawProcessingResults[$apiKeyPrefix.'processingResult']];
        }
        elseif (isset($rawProcessingResults[$apiKeyPrefix.'processingResult'][0])) {
            $processingResults = $rawProcessingResults[$apiKeyPrefix.'processingResult'];
        }

        foreach ($processingResults as $processingResult) {
            $buffer = [
                'index' => $processingResult[$apiKeyPrefix.'index'],
                'invoiceStatus' => $processingResult[$apiKeyPrefix.'invoiceStatus'],
                'technicalValidationMessages' => [],
                'businessValidationMessages' => [],
            ];

            if (isset($processingResult[$apiKeyPrefix.'technicalValidationMessages'])) {
                foreach ($processingResult[$apiKeyPrefix.'technicalValidationMessages'] as $message) {
                    $buffer['technicalValidationMessages'][] = [
                        'validationResultCode' => $message[$commonKeyPrefix.'validationResultCode'],
                        'validationErrorCode' => $message[$commonKeyPrefix.'validationErrorCode'],
                        'message' => $message[$commonKeyPrefix.'message'],
                    ];
                }
            }

            if (isset($processingResult[$apiKeyPrefix.'businessValidationMessages'])) {
                $businessValidationMessages = [];

                if (isset($processingResult[$apiKeyPrefix.'businessValidationMessages'][$apiKeyPrefix.'validationResultCode'])) {
                    $businessValidationMessages = [$processingResult[$apiKeyPrefix.'businessValidationMessages']];
                }
                else {
                    $businessValidationMessages = $processingResult[$apiKeyPrefix.'businessValidationMessages'];
                }

                foreach ($businessValidationMessages as $message) {
                    $pointer = [];

                    if (isset($message[$apiKeyPrefix.'pointer'][$apiKeyPrefix.'tag'])) {
                        $pointer['tag'] = $message[$apiKeyPrefix.'pointer'][$apiKeyPrefix.'tag'];
                    }
                    if (isset($message[$apiKeyPrefix.'pointer'][$apiKeyPrefix.'value'])) {
                        $pointer['value'] = $message[$apiKeyPrefix.'pointer'][$apiKeyPrefix.'value'];
                    }

                    $buffer['businessValidationMessages'][] = [
                        'validationResultCode' => $message[$apiKeyPrefix.'validationResultCode'],
                        'validationErrorCode' => $message[$apiKeyPrefix.'validationErrorCode'],
                        'message' => $message[$apiKeyPrefix.'message'],
                        'pointer' => $pointer,
                    ];
                }
            }

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

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return $type === QueryTransactionStatusResponse::class;
    }
}
