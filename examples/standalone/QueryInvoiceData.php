<?php

require_once __DIR__ .'/bootstrap.php';

use NAV\OnlineInvoice\Http\Request\QueryInvoiceDataRequest;
use NAV\OnlineInvoice\Http\Response\ErrorResponse;

$request = new QueryInvoiceDataRequest();
$request->setInvoiceDirection(QueryInvoiceDataRequest::INVOICE_DIRECTION_INBOUND);
$request->setInvoiceNumber('AHH/2021/0017024');

$response = initClient()->sendRequest($request);

if ($response instanceof ErrorResponse) {
    dump($response->getErrorCode(), $response->getMessage(), $response);
    exit(1);
}

dump($response);
