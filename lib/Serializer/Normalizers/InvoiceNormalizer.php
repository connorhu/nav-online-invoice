<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Enums\RequestVersionEnum;
use NAV\OnlineInvoice\Model\Address;
use NAV\OnlineInvoice\Model\Enums\CustomerVatStatusEnum;
use NAV\OnlineInvoice\Model\Interfaces\VatRateSummaryInterface;
use NAV\OnlineInvoice\Model\Invoice;
use NAV\OnlineInvoice\Model\InvoiceItem;
use NAV\OnlineInvoice\Http\Request;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class InvoiceNormalizer implements NormalizerInterface, SerializerAwareInterface, DenormalizerInterface, DenormalizerAwareInterface
{
    use SerializerAwareTrait;
    use DenormalizerAwareTrait;
    use ResponseDenormalizerTrait;

    public function __construct(
        private readonly VatRateSummaryNormalizer $vatRateSummaryNormalizer,
    ) {
    }

    protected function normalizeSupplierInfo(Invoice $invoice, string $format = null, array $context = []): array
    {
        $taxNumber = $invoice->getSupplierTaxNumber();

        $supplierInfo = [];
        $supplierInfo['supplierTaxNumber'] = [
            'base:taxpayerId' => substr($taxNumber, 0, 8),
            'base:vatCode' => substr($taxNumber, 8, 1),
            'base:countyCode' => substr($taxNumber, 9, 2),
        ];

        if ($groupMemberTaxNumber = $invoice->getSupplierGroupMemberTaxNumber()) {
            $supplierInfo['groupMemberTaxNumber'] = [
                'base:taxpayerId' => substr($groupMemberTaxNumber, 0, 8),
                'base:vatCode' => substr($groupMemberTaxNumber, 8, 1),
                'base:countyCode' => substr($groupMemberTaxNumber, 9, 2),
            ];
        }

        if ($communityVatNumber = $invoice->getSupplierCommunityVatNumber()) {
            $supplierInfo['communityVatNumber'] = $communityVatNumber;
        }

        $supplierInfo['supplierName'] = $invoice->getSupplierName();

        $supplierInfo['supplierAddress'] = $this->serializer->normalize($invoice->getSupplierAddress(), $format, $context);

        if ($bankAccountNumber = $invoice->getSupplierBankAccountNumber()) {
            $supplierInfo['supplierBankAccountNumber'] = $bankAccountNumber;
        }

        return $supplierInfo;
    }

    protected function normalizeCustomerInfo(Invoice $invoice, string $format = null, array $context = []): array
    {
        $customerInfo = [];

        $customerInfo['customerVatStatus'] = $invoice->getCustomerVatStatus()->rawString();
        if ($invoice->getCustomerVatStatus() === CustomerVatStatusEnum::PrivatePerson) {
            return $customerInfo;
        }

        if ($invoice->getCustomerTaxNumber() !== null) {
            $taxNumber = $invoice->getCustomerTaxNumber();

            $customerInfo['customerVatData'] = [
                'customerTaxNumber' => [
                    'base:taxpayerId' => substr($taxNumber, 0, 8),
                    'base:vatCode' => substr($taxNumber, 8, 1),
                    'base:countyCode' => substr($taxNumber, 9, 2),
                ],
            ];
        }

        if ($invoice->getThirdStateTaxId() !== null) {
            $customerInfo['customerVatData'] = [
                'thirdStateTaxId' => $invoice->getThirdStateTaxId(),
            ];
        }

        if ($invoice->getCustomerCommunityVatNumber() !== null) {
            $customerInfo['customerVatData'] = [
                'communityVatNumber' => $invoice->getCustomerCommunityVatNumber(),
            ];
        }

        if ($groupMemberTaxNumber = $invoice->getCustomerGroupMemberTaxNumber()) {
            $customerInfo['customerVatData']['customerTaxNumber']['groupMemberTaxNumber'] = [
                'base:taxpayerId' => substr($groupMemberTaxNumber, 0, 8),
                'base:vatCode' => substr($groupMemberTaxNumber, 8, 1),
                'base:countyCode' => substr($groupMemberTaxNumber, 9, 2),
            ];
        }

        $customerInfo['customerName'] = $invoice->getCustomerName();

        $customerInfo['customerAddress'] = $this->serializer->normalize($invoice->getCustomerAddress(), $format, $context);;

        if ($bankAccountNumber = $invoice->getCustomerBankAccountNumber()) {
            $customerInfo['customerBankAccountNumber'] = $bankAccountNumber;
        }

        return $customerInfo;
    }

    protected function normalizeDetailInfo(Invoice $invoice, string $format = null, array $context = []): array
    {
        $invoiceData = [];
        $invoiceData['invoiceCategory'] = $invoice->getInvoiceCategory();

        if ($invoice->getInvoiceDeliveryDate()) {
            $invoiceData['invoiceDeliveryDate'] = $this->serializer->normalize($invoice->getInvoiceDeliveryDate(), $format, $context);
        }

        if ($invoice->getInvoiceDeliveryPeriodStart()) {
            $invoiceData['invoiceDeliveryPeriodStart'] = $this->serializer->normalize($invoice->getInvoiceDeliveryPeriodStart(), $format, $context);
        }

        if ($invoice->getInvoiceDeliveryPeriodEnd()) {
            $invoiceData['invoiceDeliveryPeriodEnd'] = $this->serializer->normalize($invoice->getInvoiceDeliveryPeriodEnd(), $format, $context);
        }

        if ($invoice->getInvoiceAccountingDeliveryDate()) {
            $invoiceData['invoiceAccountingDeliveryDate'] = $this->serializer->normalize($invoice->getInvoiceAccountingDeliveryDate(), $format, $context);
        }

        $invoiceData['currencyCode'] = $invoice->getCurrencyCode();

        if ($invoice->getExchangeRate()) {
            $invoiceData['exchangeRate'] = $invoice->getExchangeRate();
        } else {
            $invoiceData['exchangeRate'] = 1;
        }

        if ($invoice->getSelfBillingIndicator() !== null) {
            $invoiceData['selfBillingIndicator'] = $invoice->getSelfBillingIndicator();
        }

        if ($invoice->getPaymentMethod() !== null) {
            $invoiceData['paymentMethod'] = $invoice->getPaymentMethod()->rawString();
        }

        if ($invoice->getPaymentDate()) {
            $invoiceData['paymentDate'] = $this->serializer->normalize($invoice->getPaymentDate(), $format, $context);
        }

        if ($invoice->getCashAccountingIndicator()) {
            $invoiceData['cashAccountingIndicator'] = $invoice->getCashAccountingIndicator();
        }

        $invoiceData['invoiceAppearance'] = $invoice->getInvoiceAppearance()->rawString();

        if ($invoice->getElectronicInvoiceHash()) {
            $invoiceData['electronicInvoiceHash'] = $invoice->getElectronicInvoiceHash();
        }

        if (count($invoice->getAdditionalInvoiceData()) > 0) {
            foreach ($invoice->getAdditionalInvoiceData() as $key => $data) {
                $invoiceData['additionalInvoiceData'][] = [
                    'dataName' => $key,
                    'dataDescription' => $data['description'],
                    'dataValue' => $data['value'],
                ];
            }
        }

        return $invoiceData;
    }

    protected function normalizeLines(Invoice $invoice, string $format = null, array $context = []): array
    {
        $lines = [];
        foreach ($invoice->getItems() as $item) {
            $lines[] = $this->serializer->normalize($item, $format, $context);
        }

        return $lines;
    }

    protected function normalizeSummary(Invoice $invoice, string $format = null, array $context = []): array
    {
        $invoiceSummary = [];

        $invoiceSummary['summaryNormal'] = [
            'summaryByVatRate' => [],

            'invoiceNetAmount' => $invoice->getInvoiceNetAmount(),
            'invoiceNetAmountHUF' => $invoice->getInvoiceNetAmountHUF(),

            'invoiceVatAmount' => $invoice->getInvoiceVatAmount(),
            'invoiceVatAmountHUF' => $invoice->getInvoiceVatAmountHUF(),
        ];
        $invoiceSummary['summaryGrossData'] = [
            'invoiceGrossAmount' => $invoice->getInvoiceGrossAmount(),
            'invoiceGrossAmountHUF' => $invoice->getInvoiceGrossAmountHUF(),
        ];

        foreach ($invoice->getVatRateSummaries() as $summary) {
            $invoiceSummary['summaryNormal']['summaryByVatRate'][] = $this->vatRateSummaryNormalizer->normalize($summary, $format, $context);
        }

        return $invoiceSummary;
    }

    public function normalize($invoice, $format = null, array $context = []): float|int|bool|\ArrayObject|array|string|null
    {
        $context[DateTimeNormalizer::FORMAT_KEY] = 'Y-m-d';

        $buffer = [];

        if ($format === 'invoice_xml' && $context['request_version'] === RequestVersionEnum::v20) {
            $buffer['@xmlns'] = 'http://schemas.nav.gov.hu/OSA/2.0/data';
            $buffer['@xmlns:xsi'] = 'http://www.w3.org/2001/XMLSchema-instance';
            $buffer['@xsi:schemaLocation'] = 'http://schemas.nav.gov.hu/OSA/2.0/data invoiceData.xsd';
        }
        elseif ($format === 'invoice_xml' && $context['request_version'] === RequestVersionEnum::v30) {
            $buffer['@xmlns'] = 'http://schemas.nav.gov.hu/OSA/3.0/data';
            $buffer['@xmlns:xsi'] = 'http://www.w3.org/2001/XMLSchema-instance';
            $buffer['@xsi:schemaLocation'] = 'http://schemas.nav.gov.hu/OSA/3.0/data invoiceData.xsd';
            $buffer['@xmlns:common'] = 'http://schemas.nav.gov.hu/NTCA/1.0/common';
            $buffer['@xmlns:base'] = 'http://schemas.nav.gov.hu/OSA/3.0/base';
        }

        if ($format === 'invoice_xml') {
            $buffer['@root_node_name'] = 'InvoiceData';
        }

        $supplierInfo = [];

        $buffer['invoiceNumber'] = $invoice->getInvoiceNumber();
        $buffer['invoiceIssueDate'] = $this->serializer->normalize($invoice->getInvoiceIssueDate(), $format, $context);
        $buffer['completenessIndicator'] = $invoice->isCompletenessIndicator();

        $invoiceNode = [];

        if ($invoice->getOriginalInvoiceNumber() !== null) {
            $reference = [
                'originalInvoiceNumber' => $invoice->getOriginalInvoiceNumber(),
            ];

            if ($invoice->getModifyWithoutMaster() !== false) {
                $reference['modifyWithoutMaster'] = $invoice->getModifyWithoutMaster();
            }

            if ($invoice->getModificationIndex() !== null) {
                $reference['modificationIndex'] = $invoice->getModificationIndex();
            }

            $invoiceNode['invoiceReference'] = $reference;
        }

        $invoiceNode['invoiceHead'] = [
            'supplierInfo' => $this->normalizeSupplierInfo($invoice, $format, $context),
            'customerInfo' => $this->normalizeCustomerInfo($invoice, $format, $context),
            'invoiceDetail' => $this->normalizeDetailInfo($invoice, $format, $context),
        ];
        $invoiceNode['invoiceLines'] = [
            'mergedItemIndicator' => false,
            'line' => $this->normalizeLines($invoice, $format, $context),
        ];
        $invoiceNode['invoiceSummary'] = $this->normalizeSummary($invoice, $format, $context);

        $buffer['invoiceMain'] = [
            'invoice' => $invoiceNode,
        ];

        return $buffer;
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof Invoice;
    }

    protected function denormalizeAddress(array $data, string $keyPrefix): Address
    {
        if (isset($data[$keyPrefix.'simpleAddress'])) {
            $addressInfo = $data[$keyPrefix.'simpleAddress'];

            $address = new Address();
            $address->setCountryCode($addressInfo[$keyPrefix.'countryCode']);
            $address->setPostalCode($addressInfo[$keyPrefix.'postalCode']);
            $address->setCity($addressInfo[$keyPrefix.'city']);
            $address->setAdditionalAddressDetail($addressInfo[$keyPrefix.'additionalAddressDetail']);

            return $address;
        }

        $addressInfo = $data[$keyPrefix.'detailedAddress'];

        $address = new Address();
        $address->setCountryCode($addressInfo[$keyPrefix.'countryCode']);
        $address->setRegion($addressInfo[$keyPrefix.'region']);
        $address->setPostalCode($addressInfo[$keyPrefix.'postalCode']);
        $address->setCity($addressInfo[$keyPrefix.'city']);
        $address->setStreetName($addressInfo[$keyPrefix.'streetName']);
        $address->setPublicPlaceCategory($addressInfo[$keyPrefix.'publicPlaceCategory']);
        $address->setNumber($addressInfo[$keyPrefix.'number']);
        $address->setFloor($addressInfo[$keyPrefix.'floor']);
        $address->setDoor($addressInfo[$keyPrefix.'door']);

        return $address;
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        $object = new Invoice();

        $baseKeyPrefix = self::getNamespaceKeyPrefix(ResponseDenormalizerInterface::BASE_SCHEMAS_URL_V30, $data);
        $dataKeyPrefix = self::getNamespaceKeyPrefix(ResponseDenormalizerInterface::DATA_SCHEMAS_URL_V30, $data);

        $object->setInvoiceNumber($data[$dataKeyPrefix.'invoiceNumber']);
        $object->setInvoiceIssueDate(new \DateTime($data[$dataKeyPrefix.'invoiceIssueDate']));
        $object->setCompletenessIndicator($data[$dataKeyPrefix.'completenessIndicator'] === 'true');

        $invoiceHead = $data[$dataKeyPrefix.'invoiceMain'][$dataKeyPrefix.'invoice'][$dataKeyPrefix.'invoiceHead'];
        $supplierInfo = $invoiceHead[$dataKeyPrefix.'supplierInfo'];
        $supplierTaxNumberInfo = $supplierInfo[$dataKeyPrefix.'supplierTaxNumber'];

        $object->setSupplierName($supplierInfo[$dataKeyPrefix.'supplierName']);
        $object->setSupplierCommunityVatNumber($supplierInfo[$dataKeyPrefix.'communityVatNumber']);
        $object->setSupplierAddress($this->denormalizeAddress($supplierInfo[$dataKeyPrefix.'supplierAddress'], $baseKeyPrefix));

        $taxNumber = $supplierTaxNumberInfo[$baseKeyPrefix.'taxpayerId'].'-'.$supplierTaxNumberInfo[$baseKeyPrefix.'vatCode'].'-'.$supplierTaxNumberInfo[$baseKeyPrefix.'countyCode'];
        $object->setSupplierTaxNumber($taxNumber);

        $object->setSupplierBankAccountNumber($supplierInfo[$dataKeyPrefix.'supplierBankAccountNumber']);

        $customerInfo = $invoiceHead[$dataKeyPrefix.'customerInfo'];
        $customerVatData = $customerInfo[$dataKeyPrefix.'customerVatData'];

        $object->setCustomerVatStatus(CustomerVatStatusEnum::initWithRawString($customerInfo[$dataKeyPrefix.'customerVatStatus']));
        $object->setCustomerName($customerInfo[$dataKeyPrefix.'customerName']);
        $object->setCustomerAddress($this->denormalizeAddress($customerInfo[$dataKeyPrefix.'customerAddress'], $baseKeyPrefix));

        if (isset($customerVatData[$dataKeyPrefix.'communityVatNumber'])) {
            $object->setCustomerCommunityVatNumber($customerVatData[$dataKeyPrefix.'communityVatNumber']);
        } elseif (isset($customerVatData[$dataKeyPrefix.'customerTaxNumber'])) {
            $taxNumber = $customerVatData[$dataKeyPrefix.'customerTaxNumber'];
            $taxNumber = $taxNumber[$baseKeyPrefix.'taxpayerId'].'-'.$taxNumber[$baseKeyPrefix.'vatCode'].'-'.$taxNumber[$baseKeyPrefix.'countyCode'];
            $object->setCustomerTaxNumber($taxNumber);
        }

        $invoiceDetail = $invoiceHead[$dataKeyPrefix.'invoiceDetail'];

        $object->setInvoiceCategory($invoiceDetail[$dataKeyPrefix.'invoiceCategory']);
        $object->setInvoiceDeliveryDate(new \DateTime($invoiceDetail[$dataKeyPrefix.'invoiceDeliveryDate']));
        $object->setCurrencyCode($invoiceDetail[$dataKeyPrefix.'currencyCode']);
        $object->setExchangeRate($invoiceDetail[$dataKeyPrefix.'exchangeRate']);
        $object->setSelfBillingIndicator($invoiceDetail[$dataKeyPrefix.'selfBillingIndicator'] === 'true');
        $object->setPaymentMethod($invoiceDetail[$dataKeyPrefix.'paymentMethod']);
        $object->setPaymentDate(new \DateTime($invoiceDetail[$dataKeyPrefix.'paymentDate']));
        $object->setCashAccountingIndicator($invoiceDetail[$dataKeyPrefix.'cashAccountingIndicator'] === 'true');
        $object->setInvoiceAppearance($invoiceDetail[$dataKeyPrefix.'invoiceAppearance']);

        $invoiceLines = $data[$dataKeyPrefix.'invoiceMain'][$dataKeyPrefix.'invoice'][$dataKeyPrefix.'invoiceLines'];

        $lines = isset($invoiceLines[$dataKeyPrefix.'line'][0]) ? $invoiceLines[$dataKeyPrefix.'line'] : [$invoiceLines[$dataKeyPrefix.'line']];
        foreach ($lines as $line) {
            $object->addItem($this->denormalizer->denormalize($line, InvoiceItem::class, $format, [
                InvoiceItemNormalizer::XMLNS_CONTEXT_KEY => $dataKeyPrefix,
            ]));
        }

        $summaryInfo = $data[$dataKeyPrefix.'invoiceMain'][$dataKeyPrefix.'invoice'][$dataKeyPrefix.'invoiceSummary'];
        $vatRateSummaryInfo = $summaryInfo[$dataKeyPrefix.'summaryNormal'][$dataKeyPrefix.'summaryByVatRate'];
        $summary = $this->denormalizer->denormalize($vatRateSummaryInfo, VatRateSummaryInterface::class, $format, [
            VatRateSummaryNormalizer::XMLNS_CONTEXT_KEY => $dataKeyPrefix,
        ]);

        $object->addVatRateSummary($summary); // TODO test with more vat rates

        $object->setInvoiceGrossAmount($summaryInfo[$dataKeyPrefix.'summaryGrossData'][$dataKeyPrefix.'invoiceGrossAmount']);
        $object->setInvoiceGrossAmountHUF($summaryInfo[$dataKeyPrefix.'summaryGrossData'][$dataKeyPrefix.'invoiceGrossAmountHUF']);
        $object->setInvoiceNetAmount($summaryInfo[$dataKeyPrefix.'summaryNormal'][$dataKeyPrefix.'invoiceNetAmount']);
        $object->setInvoiceNetAmountHUF($summaryInfo[$dataKeyPrefix.'summaryNormal'][$dataKeyPrefix.'invoiceNetAmountHUF']);
        $object->setInvoiceVatAmount($summaryInfo[$dataKeyPrefix.'summaryNormal'][$dataKeyPrefix.'invoiceVatAmount']);
        $object->setInvoiceVatAmountHUF($summaryInfo[$dataKeyPrefix.'summaryNormal'][$dataKeyPrefix.'invoiceVatAmountHUF']);

        return $object;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return Invoice::class === $type;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Invoice::class => true,
        ];
    }
}
