<?php

namespace Nav;

use GuzzleHttp\Client as HttpClient;
use NAV\OnlineInvoice\Http\Response;

use NAV\OnlineInvoice\TokenExchange;
use NAV\OnlineInvoice\NavRestClient;
use NAV\OnlineInvoice\Entity\InvoiceInterface;
use NAV\OnlineInvoice\Http\Request\ManageInvoiceRequest;
use NAV\OnlineInvoice\Http\Request\QueryInvoiceStatusRequest;
use NAV\OnlineInvoice\Http\Request\QueryTaxpayerRequest;

class OnlineInvoice
{
    public function __construct(NavRestClient $client, TokenExchange $tokenExchange)
    {
        $this->client = $client;
        $this->tokenExchange = $tokenExchange;
    }
    
    private $invoiceOperations = [];
    
    public function addInvoice($operation, InvoiceInterface $invoice = null)
    {
        $this->invoiceOperations[] = new InvoiceOperation($operation, $invoice);
    }
    
    const SEND_INVOCE_END_POINT_URL = '/manageInvoice';
    const SEND_INVOCE_ROOT_OBJECT_NAME = 'ManageInvoiceRequest';

    public function sendInvoices()
    {
        if (count($this->invoiceOperations) === 0) {
            return;
        }
        
        $annulment = false;
        foreach ($this->invoiceOperations as $operation) {
            if ($operation->getOperation() == InvoiceOperation::OPERATION_ANNUL) {
                $annulment = true;
                break;
            }
        }

        $token = $this->tokenExchange->fetchToken();

        $request = new ManageInvoiceRequest();
        $request->setToken($token);
        $request->setTechnicalAnnulment($annulment);
        $request->setInvoiceOperations($this->invoiceOperations);
        
        return $this->client->sendRequest($request);
    }
    
    public function getInvoiceStatus($transactionId, $originalRequest = null)
    {
        $request = new QueryInvoiceStatusRequest();
        $request->setTransactionId($transactionId);
        $request->setReturnOriginalRequest($originalRequest);
        
        return $this->client->sendRequest($request);
    }

    public function getTaxpayerInfo($taxNumber)
    {
        $request = new QueryTaxpayerRequest();
        $request->setTaxNumber($taxNumber);
        
        return $this->client->sendRequest($request);
    }
}
