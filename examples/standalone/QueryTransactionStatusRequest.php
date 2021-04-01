<?php

require __DIR__ .'/bootstrap.php';

use NAV\OnlineInvoice\Http\Request\QueryTransactionStatusRequest;

try {
    $transactionId = SETTHIS;

    $request = new QueryTransactionStatusRequest();
    $request->setTransactionId($transactionId);

    $onlineInvoiceRestClient = initClient();
    $response = $onlineInvoiceRestClient->sendRequest($request);
}
catch (GeneralErrorResponse $exception) {

    dump($exception);

}
