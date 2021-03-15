<?php

require __DIR__ .'/bootstrap.php';

use NAV\OnlineInvoice\Http\Request\QueryTaxpayerRequest;

try {
    $request = new QueryTaxpayerRequest();
    $request->setTaxNumber('69061864133');
    
    $response = $onlineInvoiceRestClient->sendRequest($request);
}
catch (GeneralErrorResponse $exception) {
    
    dump($exception);
    
}
