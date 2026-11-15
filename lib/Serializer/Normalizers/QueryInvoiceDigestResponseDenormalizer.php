<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Response\QueryInvoiceDigestResponse;
use NAV\OnlineInvoice\Model\Enums\InvoiceAppearanceEnum;
use NAV\OnlineInvoice\Model\Enums\InvoiceOperationEnum;
use NAV\OnlineInvoice\Model\Enums\PaymentMethodEnum;
use NAV\OnlineInvoice\Model\InvoiceDigest;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class QueryInvoiceDigestResponseDenormalizer implements DenormalizerInterface
{
    use ResponseDenormalizerTrait;

    protected function denormalizeV3(array $data): QueryInvoiceDigestResponse
    {
        $p = self::getNamespaceKeyPrefix(ResponseDenormalizerInterface::API_SCHEMAS_URL_V30, $data);

        $result = $data[$p.'invoiceDigestResult'];

        $response = new QueryInvoiceDigestResponse();
        $response->setCurrentPage((int) $result[$p.'currentPage']);
        $response->setAvailablePage((int) $result[$p.'availablePage']);

        foreach (self::asList($result[$p.'invoiceDigest'] ?? []) as $rawDigest) {
            $response->addInvoiceDigest(self::denormalizeDigest($rawDigest, $p));
        }

        return $response;
    }

    private static function denormalizeDigest(array $raw, string $p): InvoiceDigest
    {
        $digest = new InvoiceDigest();
        $digest->invoiceNumber = $raw[$p.'invoiceNumber'];
        $digest->batchIndex = self::int($raw, $p.'batchIndex');
        $digest->invoiceOperation = InvoiceOperationEnum::from($raw[$p.'invoiceOperation']);
        $digest->invoiceCategory = $raw[$p.'invoiceCategory'];
        $digest->invoiceIssueDate = new \DateTimeImmutable($raw[$p.'invoiceIssueDate']);
        $digest->supplierTaxNumber = $raw[$p.'supplierTaxNumber'];
        $digest->supplierGroupMemberTaxNumber = $raw[$p.'supplierGroupMemberTaxNumber'] ?? null;
        $digest->supplierName = $raw[$p.'supplierName'];
        $digest->customerTaxNumber = $raw[$p.'customerTaxNumber'] ?? null;
        $digest->customerGroupMemberTaxNumber = $raw[$p.'customerGroupMemberTaxNumber'] ?? null;
        $digest->customerName = $raw[$p.'customerName'] ?? null;

        if (isset($raw[$p.'paymentMethod'])) {
            $digest->paymentMethod = PaymentMethodEnum::initWithRawString($raw[$p.'paymentMethod']);
        }

        $digest->paymentDate = self::date($raw, $p.'paymentDate');

        if (isset($raw[$p.'invoiceAppearance'])) {
            $digest->invoiceAppearance = InvoiceAppearanceEnum::initWithRawString($raw[$p.'invoiceAppearance']);
        }

        $digest->source = $raw[$p.'source'] ?? null;
        $digest->invoiceDeliveryDate = self::date($raw, $p.'invoiceDeliveryDate');
        $digest->currency = $raw[$p.'currency'] ?? null;
        $digest->invoiceNetAmount = self::float($raw, $p.'invoiceNetAmount');
        $digest->invoiceNetAmountHUF = self::float($raw, $p.'invoiceNetAmountHUF');
        $digest->invoiceVatAmount = self::float($raw, $p.'invoiceVatAmount');
        $digest->invoiceVatAmountHUF = self::float($raw, $p.'invoiceVatAmountHUF');
        $digest->transactionId = $raw[$p.'transactionId'] ?? null;
        $digest->index = self::int($raw, $p.'index');
        $digest->originalInvoiceNumber = $raw[$p.'originalInvoiceNumber'] ?? null;
        $digest->modificationIndex = self::int($raw, $p.'modificationIndex');
        $digest->insDate = new \DateTimeImmutable($raw[$p.'insDate']);

        if (isset($raw[$p.'completenessIndicator'])) {
            $digest->completenessIndicator = BooleanNormalizer::denormalize($raw[$p.'completenessIndicator']);
        }

        return $digest;
    }

    /**
     * The XmlEncoder decodes a repeated element into a list only when it occurs more than once;
     * a single invoiceDigest is decoded into its own fields.
     */
    private static function asList(array $raw): array
    {
        if ($raw === []) {
            return [];
        }

        return array_is_list($raw) ? $raw : [$raw];
    }

    private static function int(array $raw, string $key): ?int
    {
        return isset($raw[$key]) ? (int) $raw[$key] : null;
    }

    private static function float(array $raw, string $key): ?float
    {
        return isset($raw[$key]) ? (float) $raw[$key] : null;
    }

    private static function date(array $raw, string $key): ?\DateTimeImmutable
    {
        return isset($raw[$key]) ? new \DateTimeImmutable($raw[$key]) : null;
    }

    public function denormalize($data, string $type, ?string $format = null, array $context = []): mixed
    {
        $namespace = self::getNamespaceWithUrl(ResponseDenormalizerInterface::API_SCHEMAS_URL_V30, $data);

        if ($namespace !== null) { // v3
            return $this->denormalizeV3($data);
        }

        throw new \LogicException('Unknown response interface.');
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === QueryInvoiceDigestResponse::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            QueryInvoiceDigestResponse::class => true,
        ];
    }
}
