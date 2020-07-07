<?php

namespace NAV\OnlineInvoice\Http;

use \SimpleXMLElement;
use NAV\OnlineInvoice\Http\Response\TokenExchangeResponse;
use NAV\OnlineInvoice\Http\Response\ManageInvoiceResponse;
use NAV\OnlineInvoice\Http\Response\QueryInvoiceStatusResponse;
use NAV\OnlineInvoice\Http\Response\GeneralErrorResponse;
use NAV\OnlineInvoice\Http\Response\QueryTaxpayerResponse;

class Response
{
    // protected $xml;
    public function __construct(/*$xmlObject*/)
    {
        // $this->xml = $xmlObject;
    }
    
    // public static function createResponse($responseXmlString)
    // {
    //     $xmlObject = new SimpleXMLElement($responseXmlString);
    //     switch ($xmlObject->getName()) {
    //         case 'ManageInvoiceResponse':
    //             return new ManageInvoiceResponse($xmlObject);
    //
    //         case 'QueryInvoiceDataResponse':
    //             return new QueryInvoiceDataResponse($xmlObject);
    //
    //         case 'QueryInvoiceStatusResponse':
    //             return new QueryInvoiceStatusResponse($xmlObject);
    //
    //         case 'QueryTaxpayerResponse':
    //             return new QueryTaxpayerResponse($xmlObject);
    //
    //         case 'TokenExchangeResponse':
    //             return new TokenExchangeResponse($xmlObject);
    //
    //         case 'GeneralErrorResponse':
    //             throw GeneralErrorResponse::init($xmlObject);
    //
            // case 'GeneralExceptionResponse':
            //     throw GeneralExceptionResponse::init($xmlObject);
    //
    //         default:
    //             throw new \Exception('invalid object: '. $xmlObject->getName());
    //     }
    // }
}