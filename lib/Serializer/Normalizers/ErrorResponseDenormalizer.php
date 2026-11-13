<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Response\GeneralErrorResponse;
use NAV\OnlineInvoice\Http\Response\GeneralExceptionResponse;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

/**
 * Denormalizes the two NAV error envelopes. Their shapes differ:
 *
 * - GeneralErrorResponse (OSA api schema) wraps the codes in a `result` element and may carry
 *   `technicalValidationMessages`;
 * - GeneralExceptionResponse (NTCA common schema) has `funcCode`, `errorCode` and `message`
 *   directly under the root, plus optional `notifications`. NAV sends this one e.g. for a
 *   schema violation (INVALID_REQUEST / SCHEMA_VIOLATION, HTTP 400).
 *
 * Reading the exception envelope through `result` used to raise a TypeError (null passed to a
 * string setter), so the caller never saw the NAV error code at all.
 *
 * The XmlEncoder keeps namespace prefixes in the keys (`ns2:result`), and NAV picks the prefixes
 * freely, so every key is resolved through the namespace URL declared on the root element.
 */
class ErrorResponseDenormalizer implements DenormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;
    use ResponseDenormalizerTrait;

    public function denormalize($data, string $type, ?string $format = null, array $context = []): mixed
    {
        $data = \is_array($data) ? $data : [];

        return match ($type) {
            GeneralErrorResponse::class => $this->denormalizeError($data),
            GeneralExceptionResponse::class => $this->denormalizeException($data),
            default => throw new InvalidArgumentException(sprintf('Unsupported error response type "%s".', $type)),
        };
    }

    private function denormalizeError(array $data): GeneralErrorResponse
    {
        $response = new GeneralErrorResponse();
        $common = self::getNamespaceKeyPrefix(ResponseDenormalizerInterface::COMMON_SCHEMAS_URL_V10, $data);
        $api = self::getNamespaceKeyPrefix(ResponseDenormalizerInterface::API_SCHEMAS_URL_V30, $data);
        $result = \is_array($data[$common.'result'] ?? null) ? $data[$common.'result'] : [];

        $response->setFunctionCode(self::text($result[$common.'funcCode'] ?? null));
        $response->setErrorCode(self::text($result[$common.'errorCode'] ?? null));
        $response->setMessage(self::text($result[$common.'message'] ?? null));

        foreach (self::listOf($data[$api.'technicalValidationMessages'] ?? null, $api.'validationResultCode') as $technicalValidationMessage) {
            $response->addTechnicalValidationMessage(self::withoutPrefix($technicalValidationMessage, $api));
        }

        return $response;
    }

    private function denormalizeException(array $data): GeneralExceptionResponse
    {
        $response = new GeneralExceptionResponse();
        $common = self::getNamespaceKeyPrefix(ResponseDenormalizerInterface::COMMON_SCHEMAS_URL_V10, $data);

        $response->setFunctionCode(self::text($data[$common.'funcCode'] ?? null));
        $response->setErrorCode(self::text($data[$common.'errorCode'] ?? null));
        $response->setMessage(self::text($data[$common.'message'] ?? null));

        $notifications = $data[$common.'notifications'] ?? null;
        $notifications = \is_array($notifications) ? ($notifications[$common.'notification'] ?? null) : null;
        foreach (self::listOf($notifications, $common.'notificationCode') as $notification) {
            $response->addNotification(
                self::text($notification[$common.'notificationCode'] ?? null),
                self::text($notification[$common.'notificationText'] ?? null),
            );
        }

        return $response;
    }

    private static function withoutPrefix(array $values, string $prefix): array
    {
        if ('' === $prefix) {
            return $values;
        }

        $result = [];
        foreach ($values as $key => $value) {
            $result[\is_string($key) && str_starts_with($key, $prefix) ? substr($key, \strlen($prefix)) : $key] = $value;
        }

        return $result;
    }

    /**
     * The XML decoder yields an associative array for a single repeated element and a list for
     * several; this always returns a list of arrays.
     *
     * @return list<array>
     */
    private static function listOf(mixed $value, string $singleElementKey): array
    {
        if (!\is_array($value)) {
            return [];
        }

        if (\array_key_exists($singleElementKey, $value)) {
            return [$value];
        }

        return array_values(array_filter($value, '\is_array'));
    }

    private static function text(mixed $value): ?string
    {
        return \is_scalar($value) ? (string) $value : null;
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === GeneralErrorResponse::class ||
            $type === GeneralExceptionResponse::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            GeneralErrorResponse::class => true,
            GeneralExceptionResponse::class => true,
        ];
    }
}
