<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Response\GeneralErrorResponse;
use NAV\OnlineInvoice\Http\Response\GeneralExceptionResponse;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;
use Symfony\Component\Serializer\SerializerInterface;

class ErrorResponseDenormalizer implements DenormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;
    
    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        switch ($type) {
            case GeneralErrorResponse::class:
                $response = new GeneralErrorResponse();
                break;
                
            case GeneralExceptionResponse::class:
                $response = new GeneralExceptionResponse();
                break;
        }
        
        $response->setFunctionCode($data['result']['funcCode']);
        $response->setErrorCode($data['result']['errorCode']);
        $response->setMessage($data['result']['message']);
        
        if (isset($data['technicalValidationMessages'])) {
            if (isset($data['technicalValidationMessages'][0])) {
                foreach ($data['technicalValidationMessages'] as $technicalValidationMessage) {
                    $response->addTechnicalValidationMessage($technicalValidationMessage);
                }
            }
            elseif (isset($data['technicalValidationMessages']['validationResultCode'])) {
                $response->addTechnicalValidationMessage($data['technicalValidationMessages']);
            }
        }
        
        return $response;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return $type === GeneralErrorResponse::class ||
            $type === GeneralExceptionResponse::class;
    }
}
