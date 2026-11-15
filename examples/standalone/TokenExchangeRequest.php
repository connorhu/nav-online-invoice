<?php

require_once __DIR__ .'/bootstrap.php';

use NAV\OnlineInvoice\Http\Request\TokenExchangeRequest;
use NAV\OnlineInvoice\Http\Response\ErrorResponse;

// sendRequest() returns NAV's error answer (GeneralErrorResponse / GeneralExceptionResponse,
// both implement ErrorResponse) instead of throwing it.
// To only get the token, use initClient()->getExhangeToken(): it throws a
// NAV\OnlineInvoice\Exceptions\TokenExchangeException on an error answer.
$response = initClient()->sendRequest(new TokenExchangeRequest());

if ($response instanceof ErrorResponse) {
    dump($response->getErrorCode(), $response->getMessage(), $response);
    exit(1);
}

dump($response->getExchangeToken(), $response->getValidFrom(), $response->getValidTo());
