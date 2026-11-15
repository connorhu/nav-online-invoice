<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Response\QueryInvoiceCheckResponse;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class QueryInvoiceCheckResponseDenormalizer implements DenormalizerInterface
{
    use ResponseDenormalizerTrait;

    public function denormalize($data, string $type, ?string $format = null, array $context = []): mixed
    {
        if (self::getNamespaceWithUrl(ResponseDenormalizerInterface::API_SCHEMAS_URL_V30, $data) === null) {
            throw new \LogicException('Unknown response interface.');
        }

        $p = self::getNamespaceKeyPrefix(ResponseDenormalizerInterface::API_SCHEMAS_URL_V30, $data);

        $response = new QueryInvoiceCheckResponse();
        $response->setInvoiceCheckResult(BooleanNormalizer::denormalize($data[$p.'invoiceCheckResult']));

        return $response;
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === QueryInvoiceCheckResponse::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            QueryInvoiceCheckResponse::class => true,
        ];
    }
}
