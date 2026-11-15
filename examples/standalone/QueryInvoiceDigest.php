<?php

require_once __DIR__ .'/bootstrap.php';

use NAV\OnlineInvoice\Http\Request\QueryInvoiceDigestRequest;
use NAV\OnlineInvoice\Http\Response\ErrorResponse;

$request = new QueryInvoiceDigestRequest();
$request->setInvoiceDirection(QueryInvoiceDigestRequest::INVOICE_DIRECTION_INBOUND);
$request->setPage(1);
$request->setIssueDateFrom(new \DateTime('2021-03-01'));
$request->setIssueDateTo(new \DateTime('2021-03-31'));

// Note: the library cannot denormalize a successful QueryInvoiceDigestResponse yet
// (QueryInvoiceDigestResponseDenormalizer is a stub, see issue #76), only error answers.
$response = initClient()->sendRequest($request);

if ($response instanceof ErrorResponse) {
    dump($response->getErrorCode(), $response->getMessage(), $response);
    exit(1);
}

dump($response);
