<?php

require_once __DIR__ .'/bootstrap.php';
require_once __DIR__ .'/invoice.php';

use NAV\OnlineInvoice\Exceptions\ConstraintViolationException;
use NAV\OnlineInvoice\Exceptions\TokenExchangeException;
use NAV\OnlineInvoice\Http\Request\ManageInvoiceRequest;
use NAV\OnlineInvoice\Http\Response\ErrorResponse;
use NAV\OnlineInvoice\Model\Enums\InvoiceOperationEnum;
use NAV\OnlineInvoice\Model\InvoiceOperation;
use NAV\OnlineInvoice\Validator\Exceptions\InvalidXMLException;

$request = new ManageInvoiceRequest();
$request->addInvoiceOperation(new InvoiceOperation(InvoiceOperationEnum::Create, createExampleInvoice()));

try {
    // ManageInvoiceRequest needs an exchange token: the client requests one first.
    $response = initClient()->sendRequest($request);
} catch (ConstraintViolationException $exception) {
    // the request failed the validator (validation.xml) before anything was sent
    dump((string) $exception->getViolationList());
    exit(1);
} catch (InvalidXMLException $exception) {
    // the invoice data does not match the NAV invoiceData XSD
    dump($exception->getLibXmlErrors(), $exception->getXmlContent());
    exit(1);
} catch (TokenExchangeException $exception) {
    // NAV refused the token exchange (e.g. wrong credentials)
    dump($exception->getMessage(), $exception->getResponse());
    exit(1);
}

if ($response instanceof ErrorResponse) {
    dump($response->getErrorCode(), $response->getMessage(), $response);
    exit(1);
}

// keep it to query the processing result with QueryTransactionStatusRequest.php
dump($response->getTransactionId());
