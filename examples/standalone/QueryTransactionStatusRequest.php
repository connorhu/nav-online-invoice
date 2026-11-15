<?php

require_once __DIR__ .'/bootstrap.php';

use NAV\OnlineInvoice\Http\Request\QueryTransactionStatusRequest;
use NAV\OnlineInvoice\Http\Response\ErrorResponse;

// the transaction id returned by ManageInvoiceRequest.php
$transactionId = $argv[1] ?? null;

if ($transactionId === null) {
    fwrite(STDERR, 'Usage: php '.basename(__FILE__)." <transactionId>\n");
    exit(1);
}

$request = new QueryTransactionStatusRequest();
$request->setTransactionId($transactionId);

$response = initClient()->sendRequest($request);

if ($response instanceof ErrorResponse) {
    dump($response->getErrorCode(), $response->getMessage(), $response);
    exit(1);
}

foreach ($response->getProcessingResults() as $processingResult) {
    dump($processingResult);
}
