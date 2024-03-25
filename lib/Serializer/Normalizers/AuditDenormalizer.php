<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Model\Address;
use NAV\OnlineInvoice\Http\Response\Audit;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;

class AuditDenormalizer implements DenormalizerInterface
{
    public const XMLNS_CONTEXT_KEY = '_audit_xmlns';

    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        if (!key_exists(self::XMLNS_CONTEXT_KEY, $context)) {
            // TODO create exception type
            throw new \RuntimeException('Context key missing: '. self::XMLNS_CONTEXT_KEY);
        }

        $keyPrefix = !empty($context[self::XMLNS_CONTEXT_KEY]) ? ($context[self::XMLNS_CONTEXT_KEY].':') : '';

        $object = new Audit();
        $object->setInsertDate(new \DateTime($data[$keyPrefix.'insdate'])); // it should be insDate
        $object->setInsertCustomerUser($data[$keyPrefix.'insCusUser']);
        $object->setSource($data[$keyPrefix.'source']);

        if (!empty($data[$keyPrefix.'transactionId'])) {
            $object->setTransactionId($data[$keyPrefix.'transactionId']);
        }

        if (!empty($data[$keyPrefix.'index'])) {
            $object->setIndex($data[$keyPrefix.'index']);
        }

        if (!empty($data[$keyPrefix.'batchIndex'])) {
            $object->setBatchIndex($data[$keyPrefix.'batchIndex']);
        }

        $object->setOriginalRequestVersion($data[$keyPrefix.'originalRequestVersion']);

        return $object;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null)
    {
        return $type === Audit::class;
    }
}
