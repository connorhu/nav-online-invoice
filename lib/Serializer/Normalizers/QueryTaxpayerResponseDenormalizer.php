<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Response\QueryTaxpayerResponse;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

class QueryTaxpayerResponseDenormalizer implements ContextAwareDenormalizerInterface, SerializerAwareInterface
{
    private $serializer;
    
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        $taxpayerResponse = new QueryTaxpayerResponse();
        $taxpayerResponse->setValidity($data['taxpayerValidity'] === 'true');
        
        if ($taxpayerResponse->getValidity()) {
            
            if (isset($data['infoDate'])) {
                $taxpayerResponse->setLastUpdate(new \DateTime($data['infoDate']));
            }

            if (isset($data['taxpayerData']['taxpayerName'])) {
                $taxpayerResponse->setName($data['taxpayerData']['taxpayerName']);
            }

            if (isset($data['taxpayerData']['ShortName'])) {
                $taxpayerResponse->setShortName($data['taxpayerData']['ShortName']);
            }

            if (isset($data['taxpayerData']['taxNumberDetail']['ns2:taxpayerId'])) {
                $value = (int) $data['taxpayerData']['taxNumberDetail']['ns2:taxpayerId'];
                $taxpayerResponse->setTaxpayerId($value);
            }

            if (isset($data['taxpayerData']['taxNumberDetail']['ns2:vatCode'])) {
                $value = (int) $data['taxpayerData']['taxNumberDetail']['ns2:vatCode'];
                $taxpayerResponse->setVatCode($value);
            }

            if (isset($data['taxpayerData']['vatGroupMembership'])) {
                $value = (int) $data['taxpayerData']['vatGroupMembership'];
                $taxpayerResponse->setVatGroupMembership($value);
            }
            
            if (isset($data['taxpayerData']['taxpayerAddressList'])) {
                $items = $data['taxpayerData']['taxpayerAddressList']['taxpayerAddressItem'];
                
                if (isset($items['taxpayerAddressType'])) {
                    $items = [$items];
                }
                
                foreach ($items as $item) {
                    $buffer = [
                        'type' => $item['taxpayerAddressType'],
                    ];
                    
                    if (isset($item['taxpayerAddress']['ns2:countryCode'])) {
                        $buffer['countryCode'] = $item['taxpayerAddress']['ns2:countryCode'];
                    }

                    if (isset($item['taxpayerAddress']['ns2:region'])) {
                        $buffer['region'] = $item['taxpayerAddress']['ns2:region'];
                    }

                    if (isset($item['taxpayerAddress']['ns2:postalCode'])) {
                        $buffer['postalCode'] = $item['taxpayerAddress']['ns2:postalCode'];
                    }

                    if (isset($item['taxpayerAddress']['ns2:city'])) {
                        $buffer['city'] = $item['taxpayerAddress']['ns2:city'];
                    }

                    if (isset($item['taxpayerAddress']['ns2:streetName'])) {
                        $buffer['streetName'] = $item['taxpayerAddress']['ns2:streetName'];
                    }

                    if (isset($item['taxpayerAddress']['ns2:publicPlaceCategory'])) {
                        $buffer['publicPlaceCategory'] = $item['taxpayerAddress']['ns2:publicPlaceCategory'];
                    }

                    if (isset($item['taxpayerAddress']['ns2:number'])) {
                        $buffer['number'] = $item['taxpayerAddress']['ns2:number'];
                    }

                    if (isset($item['taxpayerAddress']['ns2:building'])) {
                        $buffer['building'] = $item['taxpayerAddress']['ns2:building'];
                    }

                    if (isset($item['taxpayerAddress']['ns2:staircase'])) {
                        $buffer['staircase'] = $item['taxpayerAddress']['ns2:staircase'];
                    }

                    if (isset($item['taxpayerAddress']['ns2:floor'])) {
                        $buffer['floor'] = $item['taxpayerAddress']['ns2:floor'];
                    }

                    if (isset($item['taxpayerAddress']['ns2:door'])) {
                        $buffer['door'] = $item['taxpayerAddress']['ns2:door'];
                    }

                    if (isset($item['taxpayerAddress']['ns2:lotNumber'])) {
                        $buffer['lotNumber'] = $item['taxpayerAddress']['ns2:lotNumber'];
                    }
                    
                    $taxpayerResponse->addAddress($buffer);
                }
            }
        }
        
        return $taxpayerResponse;
    }

    public function supportsDenormalization($data, string $type, ?string $format = null, array $context = [])
    {
        return $type === QueryTaxpayerResponse::class;
    }
}
