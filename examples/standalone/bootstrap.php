<?php

require_once __DIR__ .'/../../vendor/autoload.php';

use NAV\OnlineInvoice\Factories\VatRateFactoryInterface;
use NAV\OnlineInvoice\Model\Interfaces\VatRateInterface;
use NAV\OnlineInvoice\Model\Traits\VatRateTrait;
use NAV\OnlineInvoice\OnlineInvoiceRestClient;
use NAV\OnlineInvoice\Providers\CompactDataProvider;
use NAV\OnlineInvoice\Serializer\Encoder\RequestEncoder;
use NAV\OnlineInvoice\Serializer\Normalizers;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validation;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * A standalone VAT rate. The library denormalizes VAT rates into your own model
 * classes, so it asks a VatRateFactoryInterface for new instances. Invoice lines
 * and VAT summaries use InvoiceItem / VatRateSummary; this factory is only the
 * fallback for a bare VatRateInterface.
 */
final class ExampleVatRate implements VatRateInterface
{
    use VatRateTrait;
}

final class ExampleVatRateFactory implements VatRateFactoryInterface
{
    public function createVatRate(): VatRateInterface
    {
        return new ExampleVatRate();
    }
}

function createSerializer(CompactDataProvider $dataProvider): Serializer
{
    $encoders = [
        new RequestEncoder(),
        new XmlEncoder(),
    ];

    $requestNormalizer = new Normalizers\RequestNormalizer($dataProvider);
    $vatRateNormalizer = new Normalizers\VatRateNormalizer(new ExampleVatRateFactory());
    $vatRateSummaryNormalizer = new Normalizers\VatRateSummaryNormalizer($vatRateNormalizer);

    $normalizers = [
        // requests
        $requestNormalizer,
        new Normalizers\TokenExchangeRequestNormalizer($requestNormalizer),
        new Normalizers\QueryTaxpayerRequestNormalizer($requestNormalizer),
        new Normalizers\QueryInvoiceDigestRequestNormalizer($requestNormalizer),
        new Normalizers\QueryInvoiceDataRequestNormalizer($requestNormalizer),
        new Normalizers\QueryTransactionStatusRequestNormalizer($requestNormalizer),
        new Normalizers\ManageInvoiceRequestNormalizer($requestNormalizer, $dataProvider),
        new Normalizers\UserNormalizer($dataProvider),
        new Normalizers\SoftwareNormalizer(),
        new Normalizers\HeaderNormalizer(),

        // invoice data
        new Normalizers\InvoiceNormalizer($vatRateSummaryNormalizer),
        new Normalizers\InvoiceItemNormalizer($vatRateNormalizer),
        $vatRateSummaryNormalizer,
        $vatRateNormalizer,
        new Normalizers\ProductCodeNormalizer(),
        new Normalizers\AddressNormalizer(),
        new DateTimeNormalizer(),

        // responses
        new Normalizers\TokenExchangeResponseDenormalizer($dataProvider),
        new Normalizers\QueryTaxpayerResponseDenormalizer(),
        new Normalizers\QueryInvoiceDigestResponseDenormalizer(),
        new Normalizers\QueryInvoiceDataResponseDenormalizer(),
        new Normalizers\QueryTransactionStatusResponseDenormalizer(),
        new Normalizers\ManageInvoiceResponseDenormalizer(),
        new Normalizers\ErrorResponseDenormalizer(),
        new Normalizers\AuditDenormalizer(),
    ];

    return new Serializer($normalizers, $encoders);
}

/**
 * @param HttpClientInterface|null $httpClient defaults to a real HTTP client
 * @param string|null $infoJsonPath defaults to info.json next to this file (copy info.json.dist)
 */
function initClient(?HttpClientInterface $httpClient = null, ?string $infoJsonPath = null): OnlineInvoiceRestClient
{
    $dataProvider = new CompactDataProvider($infoJsonPath ?? __DIR__ .'/info.json');

    $validator = Validation::createValidatorBuilder()
        ->addXmlMapping(__DIR__ .'/../../lib/Resources/validator/validation.xml')
        ->getValidator();

    $onlineInvoiceRestClient = new OnlineInvoiceRestClient(
        $dataProvider,
        $validator,
        createSerializer($dataProvider),
        $dataProvider,
        $dataProvider,
        $dataProvider,
        null,
        $httpClient,
    );
    $onlineInvoiceRestClient->setVersion(OnlineInvoiceRestClient::VERSION_30);

    return $onlineInvoiceRestClient;
}
