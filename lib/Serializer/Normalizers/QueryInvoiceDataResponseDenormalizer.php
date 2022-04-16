<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use http\Encoding\Stream\Deflate;
use NAV\OnlineInvoice\Http\Response\QueryInvoiceDataResponse;
use NAV\OnlineInvoice\Http\Response\QueryTaxpayerResponse;
use NAV\OnlineInvoice\Http\Response\QueryInvoiceDigestResponse;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class QueryInvoiceDataResponseDenormalizer implements ContextAwareDenormalizerInterface
{
    use ResponseDenormalizerTrait;

    protected function denormalizeV3($data): QueryTaxpayerResponse
    {
        $namespace = self::getNamespaceWithUrl(ResponseDenormalizerInterface::API_SCHEMAS_URL_V30, $data);
        $apiKeyPrefix = $namespace !== '' ? $namespace.':' : $namespace;

        $namespace = self::getNamespaceWithUrl(ResponseDenormalizerInterface::BASE_SCHEMAS_URL_V30, $data);
        $baseKeyPrefix = $namespace !== '' ? $namespace.':' : $namespace;

        $taxpayerResponse = new QueryTaxpayerResponse();
        $taxpayerResponse->setValidity($data[$apiKeyPrefix.'taxpayerValidity'] === 'true');

        if ($taxpayerResponse->getValidity()) {

            if (isset($data['infoDate'])) {
                $taxpayerResponse->setLastUpdate(new \DateTime($data[$apiKeyPrefix.'infoDate']));
            }

            $taxpayerData = $data[$apiKeyPrefix.'taxpayerData'];

            if (isset($taxpayerData[$apiKeyPrefix.'taxpayerName'])) {
                $taxpayerResponse->setName($taxpayerData[$apiKeyPrefix.'taxpayerName']);
            }

            if (isset($taxpayerData[$apiKeyPrefix.'taxpayerShortName'])) {
                $taxpayerResponse->setShortName($taxpayerData[$apiKeyPrefix.'taxpayerShortName']);
            }

            if (isset($taxpayerData[$apiKeyPrefix.'taxNumberDetail'][$baseKeyPrefix.'taxpayerId'])) {
                $value = (int) $taxpayerData[$apiKeyPrefix.'taxNumberDetail'][$baseKeyPrefix.'taxpayerId'];
                $taxpayerResponse->setTaxpayerId($value);
            }

            if (isset($taxpayerData[$apiKeyPrefix.'taxNumberDetail'][$baseKeyPrefix.'vatCode'])) {
                $value = (int) $taxpayerData[$apiKeyPrefix.'taxNumberDetail'][$baseKeyPrefix.'vatCode'];
                $taxpayerResponse->setVatCode($value);
            }

            if (isset($taxpayerData[$apiKeyPrefix.'taxNumberDetail'][$baseKeyPrefix.'countryCode'])) {
                $value = (int) $taxpayerData[$apiKeyPrefix.'taxNumberDetail'][$baseKeyPrefix.'countryCode'];
                $taxpayerResponse->setCountryCode($value);
            }

            if (isset($taxpayerData[$apiKeyPrefix.'vatGroupMembership'])) {
                $value = (int) $taxpayerData[$apiKeyPrefix.'vatGroupMembership'];
                $taxpayerResponse->setVatGroupMembership($value);
            }

            if (isset($taxpayerData[$apiKeyPrefix.'taxpayerAddressList'])) {
                $items = $taxpayerData[$apiKeyPrefix.'taxpayerAddressList'][$apiKeyPrefix.'taxpayerAddressItem'];

                if (isset($items[$apiKeyPrefix.'taxpayerAddressType'])) {
                    $items = [$items];
                }

                foreach ($items as $item) {
                    $buffer = [
                        'type' => $item[$apiKeyPrefix.'taxpayerAddressType'],
                    ];

                    $address = $item[$apiKeyPrefix.'taxpayerAddress'];

                    // TODO Address denormalizer

                    if (isset($address[$baseKeyPrefix.'countryCode'])) {
                        $buffer['countryCode'] = $address[$baseKeyPrefix.'countryCode'];
                    }

                    if (isset($address[$baseKeyPrefix.'region'])) {
                        $buffer['region'] = $address[$baseKeyPrefix.'region'];
                    }

                    if (isset($address[$baseKeyPrefix.'postalCode'])) {
                        $buffer['postalCode'] = $address[$baseKeyPrefix.'postalCode'];
                    }

                    if (isset($address[$baseKeyPrefix.'city'])) {
                        $buffer['city'] = $address[$baseKeyPrefix.'city'];
                    }

                    if (isset($address[$baseKeyPrefix.'streetName'])) {
                        $buffer['streetName'] = $address[$baseKeyPrefix.'streetName'];
                    }

                    if (isset($address[$baseKeyPrefix.'publicPlaceCategory'])) {
                        $buffer['publicPlaceCategory'] = $address[$baseKeyPrefix.'publicPlaceCategory'];
                    }

                    if (isset($address[$baseKeyPrefix.'number'])) {
                        $buffer['number'] = $address[$baseKeyPrefix.'number'];
                    }

                    if (isset($address[$baseKeyPrefix.'building'])) {
                        $buffer['building'] = $address[$baseKeyPrefix.'building'];
                    }

                    if (isset($address[$baseKeyPrefix.'staircase'])) {
                        $buffer['staircase'] = $address[$baseKeyPrefix.'staircase'];
                    }

                    if (isset($address[$baseKeyPrefix.'floor'])) {
                        $buffer['floor'] = $address[$baseKeyPrefix.'floor'];
                    }

                    if (isset($address[$baseKeyPrefix.'door'])) {
                        $buffer['door'] = $address[$baseKeyPrefix.'door'];
                    }

                    if (isset($address[$baseKeyPrefix.'lotNumber'])) {
                        $buffer['lotNumber'] = $address[$baseKeyPrefix.'lotNumber'];
                    }

                    $taxpayerResponse->addAddress($buffer);
                }
            }
        }

        return $taxpayerResponse;
    }

    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        $namespace = self::getNamespaceWithUrl(ResponseDenormalizerInterface::API_SCHEMAS_URL_V30, $data);

        $content = base64_decode($data['ns2:invoiceDataResult']['ns2:invoiceData']);

        if ($data['ns2:invoiceDataResult']['ns2:compressedContentIndicator'] === 'true') {
            dump($content);
            $content = gzinflate($content);
            dump($content);
        }

        dump($content);

        exit;

        if ($namespace !== null) { // v3
            return $this->denormalizeV3($data);
        }

        throw new \LogicException('Unknown response interface.');
    }

    public function supportsDenormalization($data, string $type, ?string $format = null, array $context = [])
    {
        return $type === QueryInvoiceDataResponse::class;
    }
}
