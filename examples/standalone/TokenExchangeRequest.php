<?php

require __DIR__ .'/bootstrap.php';

use NAV\OnlineInvoice\Http\Request\TokenExchangeRequest;

try {
    $request = new TokenExchangeRequest();
    
    $response = $onlineInvoiceRestClient->sendRequest($request);
    
    dump($response->getExchangeToken(), $response->getValidFrom(), $response->getValidTo());
}
catch (GeneralErrorResponse $exception) {
    
    dump($exception);
    
}
