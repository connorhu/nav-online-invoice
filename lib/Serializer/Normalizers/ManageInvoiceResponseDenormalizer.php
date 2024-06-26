<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Response\ManageInvoiceResponse;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ManageInvoiceResponseDenormalizer implements DenormalizerInterface
{
    use ResponseDenormalizerTrait;

    public function denormalize($data, string $type, ?string $format = null, array $context = []): mixed
    {
        $namespace = self::getNamespaceWithUrl(ResponseDenormalizerInterface::API_SCHEMAS_URL_V30, $data);

        if ($namespace !== null) {
            $keyPrefix = $namespace !== '' ? $namespace.':' : $namespace;

            $response = new ManageInvoiceResponse();
            $response->setTransactionId($data[$keyPrefix.'transactionId']);
            return $response;
        }

        throw new \LogicException('Unknown response version.');
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return $type === ManageInvoiceResponse::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            ManageInvoiceResponse::class => true,
        ];
    }
}
