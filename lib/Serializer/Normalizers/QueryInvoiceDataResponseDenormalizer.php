<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Model\Invoice;
use NAV\OnlineInvoice\Http\Request\Header;
use NAV\OnlineInvoice\Http\Request\Software;
use NAV\OnlineInvoice\Http\Response\Audit;
use NAV\OnlineInvoice\Http\Response\QueryInvoiceDataResponse;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class QueryInvoiceDataResponseDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface, SerializerAwareInterface
{
    use ResponseDenormalizerTrait;
    use DenormalizerAwareTrait;
    use SerializerAwareTrait;

    protected function denormalizeV3($data): QueryInvoiceDataResponse
    {
        dump($data);
        exit;
    }

    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        $commonNamespacePrefix = self::getNamespaceWithUrl(ResponseDenormalizerInterface::COMMON_SCHEMAS_URL_V10, $data);
        $commonKeyPrefix = self::getNamespaceKeyPrefix(ResponseDenormalizerInterface::COMMON_SCHEMAS_URL_V10, $data);

        $apiNamespacePrefix = self::getNamespaceWithUrl(ResponseDenormalizerInterface::API_SCHEMAS_URL_V30, $data);
        $apiKeyPrefix = self::getNamespaceKeyPrefix(ResponseDenormalizerInterface::API_SCHEMAS_URL_V30, $data);

        $header = $this->denormalizer->denormalize($data[$commonKeyPrefix.'header'], Header::class, $format, [
            HeaderNormalizer::XMLNS_CONTEXT_KEY => $commonNamespacePrefix,
        ]);

        $software = $this->denormalizer->denormalize($data[$apiKeyPrefix.'software'], Software::class, $format, [
            SoftwareNormalizer::XMLNS_CONTEXT_KEY => $apiNamespacePrefix,
        ]);

        $invoiceDataResult = $data[$apiKeyPrefix.'invoiceDataResult'];

        $audit = $this->denormalizer->denormalize($invoiceDataResult[$apiKeyPrefix.'auditData'], Audit::class, $format, [
            AuditDenormalizer::XMLNS_CONTEXT_KEY => $apiNamespacePrefix,
        ]);

        $object = new QueryInvoiceDataResponse();
        $object->setHeader($header);
        $object->setSoftware($software);
        $object->setAudit($audit);
        $object->setCompressedContentIndicator($invoiceDataResult[$apiKeyPrefix.'compressedContentIndicator'] === 'true');

        $stringInvoiceContent = base64_decode($invoiceDataResult[$apiKeyPrefix.'invoiceData']);
        if ($object->getCompressedContentIndicator()) {
            $stringInvoiceContent = gzinflate($stringInvoiceContent);
        }

        $invoice = $this->serializer->deserialize($stringInvoiceContent, Invoice::class, 'xml');

        $object->setInvoiceData($invoice);

        return $object;
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return $type === QueryInvoiceDataResponse::class;
    }
}
