<?php

require __DIR__ .'/bootstrap.php';

use NAV\OnlineInvoice\Http\Request\ManageInvoiceRequest;
use NAV\OnlineInvoice\Model\Address;
use NAV\OnlineInvoice\Model\Enums\InvoiceOperationEnum;
use NAV\OnlineInvoice\Model\Invoice;
use NAV\OnlineInvoice\Model\InvoiceOperation;

try {
    $request = new ManageInvoiceRequest();

    $invoice = new Invoice();
    $invoice->setSupplierName('Supplier Name');
    $invoice->setSupplierTaxNumber('123456789-1-10');
    $invoice->setSupplierBankAccountNumber('123456789');
    $invoice->setSupplierAddress((new Address())
        ->setCountryCode('HU')
        ->setCity('Budapest')
        ->setPostalCode('1063')
        ->setAdditionalAddressDetail('Podmaniczky út 1'));

    $invoice->setCustomerName('Customer Name');
    $invoice->setCustomerTaxNumber('123456789-1-10');
    $invoice->setCustomerBankAccountNumber('123456789');
    $invoice->setCustomerAddress((new Address())
        ->setCountryCode('HU')
        ->setCity('Budapest')
        ->setPostalCode('1063')
        ->setAdditionalAddressDetail('Podmaniczky út 2'));

    $invoiceOperation = new InvoiceOperation(
        InvoiceOperationEnum::Create,
        $invoice,
    );

    $request->addInvoiceOperation($invoiceOperation);

    $onlineInvoiceRestClient = initClient();
    $response = $onlineInvoiceRestClient->sendRequest($request);

    dump($response);

} catch (GeneralErrorResponse $exception) {
    
    dump($exception);
    
}
