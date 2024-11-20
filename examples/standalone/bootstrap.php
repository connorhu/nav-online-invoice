<?php

require __DIR__ .'/../../vendor/autoload.php';

use NAV\OnlineInvoice\OnlineInvoiceRestClient;
use NAV\OnlineInvoice\Providers\CompactDataProvider;
use NAV\OnlineInvoice\Serializer\Encoder\RequestEncoder;
use NAV\OnlineInvoice\Serializer\Normalizers;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validation;

function initClient(): OnlineInvoiceRestClient
{
    $dataProvider = new CompactDataProvider(__DIR__ .'/info.json');

    $encoders = [
        new RequestEncoder(),
        new XmlEncoder(),
    ];

    $requestNormalizer = new Normalizers\RequestNormalizer($dataProvider);

    $normalizers = [
        $requestNormalizer,
        new Normalizers\QueryTaxpayerRequestNormalizer($requestNormalizer),
        new DateTimeNormalizer(),
        $queryInvoiceDigest = new Normalizers\QueryInvoiceDigestRequestNormalizer($requestNormalizer),
        new Normalizers\TokenExchangeRequestNormalizer($requestNormalizer),
        new Normalizers\QueryInvoiceDataRequestNormalizer($requestNormalizer),
        new Normalizers\QueryInvoiceDataResponseDenormalizer(),
        new Normalizers\TokenExchangeResponseDenormalizer($dataProvider),
        new Normalizers\QueryInvoiceDigestResponseDenormalizer(),
        new Normalizers\UserNormalizer($dataProvider),
        new Normalizers\SoftwareNormalizer(),
        new Normalizers\HeaderNormalizer(),
        new Normalizers\ManageInvoiceRequestNormalizer($requestNormalizer, $dataProvider),
        new Normalizers\InvoiceNormalizer(),
        new Normalizers\AddressNormalizer(),
        new Normalizers\ManageInvoiceResponseDenormalizer(),
    ];

    $serializer = new Serializer($normalizers, $encoders);
    $queryInvoiceDigest->setNormalizer($serializer);
    $validator = Validation::createValidator();

    $onlineInvoiceRestClient = new OnlineInvoiceRestClient($dataProvider, $validator, $serializer, $dataProvider, $dataProvider, $dataProvider);
    $onlineInvoiceRestClient->setVersion(OnlineInvoiceRestClient::VERSION_30);

    return $onlineInvoiceRestClient;
}
