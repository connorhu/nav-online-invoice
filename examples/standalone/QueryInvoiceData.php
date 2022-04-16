<?php

require __DIR__ .'/bootstrap.php';

use NAV\OnlineInvoice\Http\Request\QueryInvoiceDataRequest;

try {
    $request = new QueryInvoiceDataRequest();
    $request->setInvoiceDirection(QueryInvoiceDataRequest::INVOICE_DIRECTION_INBOUND);
    $request->setInvoiceNumber('AHH/2021/0017024');

    $onlineInvoiceRestClient = initClient();
    $response = $onlineInvoiceRestClient->sendRequest($request);
}
catch (GeneralErrorResponse $exception) {
    
    dump($exception);
    
}
