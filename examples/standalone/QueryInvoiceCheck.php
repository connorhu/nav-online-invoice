<?php

require_once __DIR__ .'/bootstrap.php';

use NAV\OnlineInvoice\Http\Request\QueryInvoiceCheckRequest;
use NAV\OnlineInvoice\Http\Response\ErrorResponse;

$request = new QueryInvoiceCheckRequest();
$request->setInvoiceDirection(QueryInvoiceCheckRequest::INVOICE_DIRECTION_OUTBOUND);
$request->setInvoiceNumber('AHH/2021/0017024');
// optional: $request->setBatchIndex(1); (batch modifications)
// optional, INBOUND only: $request->setSupplierTaxNumber('12345678');

$response = initClient()->sendRequest($request);

if ($response instanceof ErrorResponse) {
    dump($response->getErrorCode(), $response->getMessage(), $response);
    exit(1);
}

dump($response->getInvoiceCheckResult());
