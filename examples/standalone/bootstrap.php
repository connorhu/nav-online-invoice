<?php

require __DIR__ .'/../../vendor/autoload.php';

use NAV\OnlineInvoice\OnlineInvoiceRestClient;
use NAV\OnlineInvoice\Providers\CryptoToolsProvider;
use NAV\OnlineInvoice\Providers\CompactDataProvider;
use NAV\OnlineInvoice\Serializer\Encoder\RequestEncoder;
use NAV\OnlineInvoice\Serializer\Normalizers\HeaderNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\QueryTaxpayerRequestNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\RequestNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\TokenExchangeRequestNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\TokenExchangeResponseDenormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\UserNormalizer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
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
        new TokenExchangeRequestNormalizer($requestNormalizer),
        new TokenExchangeResponseDenormalizer($dataProvider),
        new UserNormalizer($dataProvider),
        new SoftwareNormalizer(),
        new HeaderNormalizer(),
    ];

    $serializer = new Serializer($normalizers, $encoders);
    $validator = Validation::createValidator();

    $onlineInvoiceRestClient = new OnlineInvoiceRestClient($dataProvider, $validator, $serializer, $dataProvider, $dataProvider, $dataProvider);
    $onlineInvoiceRestClient->setVersion(OnlineInvoiceRestClient::VERSION_30);

    return $onlineInvoiceRestClient;
}
