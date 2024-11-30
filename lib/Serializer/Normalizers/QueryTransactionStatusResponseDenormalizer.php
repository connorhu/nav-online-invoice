<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Enums\RequestVersionEnum;
use NAV\OnlineInvoice\Http\Response\QueryTransactionStatusResponse;
use NAV\OnlineInvoice\Model\BusinessValidationMessage;
use NAV\OnlineInvoice\Model\Enums\InvoiceStatusEnum;
use NAV\OnlineInvoice\Model\Enums\ValidationResultCodeEnum;
use NAV\OnlineInvoice\Model\ProcessingResult;
use NAV\OnlineInvoice\Model\TechnicalValidationMessage;
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
        $response->setOriginalRequestVersion(RequestVersionEnum::initWithRawString($rawProcessingResults[$apiKeyPrefix.'originalRequestVersion']));

        $processingResults = [];
        if (isset($rawProcessingResults[$apiKeyPrefix.'processingResult'][$apiKeyPrefix.'index'])) {
            $processingResults = [$rawProcessingResults[$apiKeyPrefix.'processingResult']];
        } elseif (isset($rawProcessingResults[$apiKeyPrefix.'processingResult'][0])) {
            $processingResults = $rawProcessingResults[$apiKeyPrefix.'processingResult'];
        }

        foreach ($processingResults as $processingResult) {
            $resultObject = new ProcessingResult();
            $resultObject->index = (int) $processingResult[$apiKeyPrefix.'index'];

            if (isset($processingResult[$apiKeyPrefix.'batchIndex'])) {
                $resultObject->batchIndex = (int) $processingResult[$apiKeyPrefix.'batchIndex'];
            }

            $resultObject->status = InvoiceStatusEnum::initWithRawString($processingResult[$apiKeyPrefix.'invoiceStatus']);
            $resultObject->compressedContent = $processingResult[$apiKeyPrefix.'compressedContentIndicator'];

            if (isset($processingResult[$apiKeyPrefix.'technicalValidationMessages'])) {
                foreach ($processingResult[$apiKeyPrefix.'technicalValidationMessages'] as $message) {
                    $technicalValidationMessage = new TechnicalValidationMessage();
                    $technicalValidationMessage->resultCode = ValidationResultCodeEnum::initWithRawString($message[$commonKeyPrefix.'validationResultCode']);
                    $technicalValidationMessage->errorCode = $message[$commonKeyPrefix.'validationErrorCode'];
                    $technicalValidationMessage->message = $message[$commonKeyPrefix.'message'];

                    $resultObject->technicalValidationMessages[] = $technicalValidationMessage;
                }
            }

            if (isset($processingResult[$apiKeyPrefix.'businessValidationMessages'])) {
                if (isset($processingResult[$apiKeyPrefix.'businessValidationMessages'][$apiKeyPrefix.'validationResultCode'])) {
                    $businessValidationMessages = [$processingResult[$apiKeyPrefix.'businessValidationMessages']];
                } else {
                    $businessValidationMessages = $processingResult[$apiKeyPrefix.'businessValidationMessages'];
                }

                foreach ($businessValidationMessages as $message) {
                    $businessValidationMessage = new BusinessValidationMessage();
                    $businessValidationMessage->resultCode = ValidationResultCodeEnum::initWithRawString($message[$commonKeyPrefix.'validationResultCode']);
                    $businessValidationMessage->errorCode = $message[$commonKeyPrefix.'validationErrorCode'];
                    $businessValidationMessage->message = $message[$commonKeyPrefix.'message'];

                    if (isset($message[$apiKeyPrefix.'pointer'][$apiKeyPrefix.'tag'])) {
                        $businessValidationMessage->pointerTag = $message[$apiKeyPrefix.'pointer'][$apiKeyPrefix.'tag'];
                    }
                    if (isset($message[$apiKeyPrefix.'pointer'][$apiKeyPrefix.'value'])) {
                        $businessValidationMessage->pointerValue = $message[$apiKeyPrefix.'pointer'][$apiKeyPrefix.'value'];
                    }
                    if (isset($message[$apiKeyPrefix.'pointer'][$apiKeyPrefix.'line'])) {
                        $businessValidationMessage->pointerLine = $message[$apiKeyPrefix.'pointer'][$apiKeyPrefix.'line'];
                    }
                    if (isset($message[$apiKeyPrefix.'pointer'][$apiKeyPrefix.'originalInvoiceNumber'])) {
                        $businessValidationMessage->pointerOriginalInvoiceNumber = $message[$apiKeyPrefix.'pointer'][$apiKeyPrefix.'originalInvoiceNumber'];
                    }

                    $resultObject->businessValidationMessages[] = $businessValidationMessage;
                }
            }

            $response->addProcessingResult($resultObject);
        }

        return $response;
    }

    public function denormalize($data, string $type, ?string $format = null, array $context = []): mixed
    {
        $namespace = self::getNamespaceWithUrl(ResponseDenormalizerInterface::API_SCHEMAS_URL_V30, $data);

        if ($namespace !== null) { // v3
            return $this->denormalizeV3($data);
        }

        throw new \LogicException('Unknown response interface.');
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $type === QueryTransactionStatusResponse::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            QueryTransactionStatusResponse::class => true,
        ];
    }
}
