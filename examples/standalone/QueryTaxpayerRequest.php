<?php

require_once __DIR__ .'/bootstrap.php';

use NAV\OnlineInvoice\Http\Request\QueryTaxpayerRequest;
use NAV\OnlineInvoice\Http\Response\ErrorResponse;

$request = new QueryTaxpayerRequest();
$request->setTaxNumber('69061864'); // the first 8 digits of the tax number

$response = initClient()->sendRequest($request);

if ($response instanceof ErrorResponse) {
    dump($response->getErrorCode(), $response->getMessage(), $response);
    exit(1);
}

dump($response->getValidity(), $response->getName(), $response->getAddresses());
