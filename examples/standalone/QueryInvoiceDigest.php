<?php

require __DIR__ .'/bootstrap.php';

use NAV\OnlineInvoice\Http\Request\QueryInvoiceDigestRequest;

try {
    $request = new QueryInvoiceDigestRequest();
    $request->setInvoiceDirection(QueryInvoiceDigestRequest::INVOICE_DIRECTION_INBOUND);
    $request->setPage(1);
    $request->setIssueDateFrom(new \DateTime('2021-03-01'));
    $request->setIssueDateTo(new \DateTime('2021-03-31'));

    $onlineInvoiceRestClient = initClient();
    $response = $onlineInvoiceRestClient->sendRequest($request);
}
catch (GeneralErrorResponse $exception) {
    
    dump($exception);
    
}
