<?php

require __DIR__ .'/../../vendor/autoload.php';

use NAV\OnlineInvoice\OnlineInvoiceRestClient;
use NAV\OnlineInvoice\Providers\CryptoToolsProvider;
use NAV\OnlineInvoice\Providers\CompactDataProvider;
use NAV\OnlineInvoice\Serializer\Encoder\RequestEncoder;
use NAV\OnlineInvoice\Serializer\Normalizers\HeaderNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\QueryInvoiceDigestRequestNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\QueryTaxpayerRequestNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\RequestNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\QueryInvoiceDataRequestNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\TokenExchangeRequestNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\TokenExchangeResponseDenormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\QueryInvoiceDataResponseDenormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\QueryInvoiceDigestResponseDenormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\UserNormalizer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validation;

function initClient(): OnlineInvoiceRestClient
{
    $dataProvider = new CompactDataProvider(__DIR__ .'/info.json');

    $encoders = [
        new RequestEncoder(new XmlEncoder()),
    ];

    $requestNormalizer = new RequestNormalizer($dataProvider);

    $normalizers = [
        $requestNormalizer,
        new QueryTaxpayerRequestNormalizer($requestNormalizer),
        new DateTimeNormalizer(),
        $queryInvoiceDigest = new QueryInvoiceDigestRequestNormalizer($requestNormalizer),
        new TokenExchangeRequestNormalizer($requestNormalizer),
        new QueryInvoiceDataRequestNormalizer($requestNormalizer),
        new QueryInvoiceDataResponseDenormalizer(),
        new TokenExchangeResponseDenormalizer($dataProvider),
        new QueryInvoiceDigestResponseDenormalizer(),
        new UserNormalizer($dataProvider),
        new SoftwareNormalizer(),
        new HeaderNormalizer(),
    ];

    $serializer = new Serializer($normalizers, $encoders);
    $queryInvoiceDigest->setNormalizer($serializer);
    $validator = Validation::createValidator();

    $onlineInvoiceRestClient = new OnlineInvoiceRestClient($dataProvider, $validator, $serializer, $dataProvider, $dataProvider, $dataProvider);
    $onlineInvoiceRestClient->setVersion(OnlineInvoiceRestClient::VERSION_30);

    return $onlineInvoiceRestClient;
}
