<?php

namespace NAV\OnlineInvoice\Providers;

use NAV\OnlineInvoice\Http\Response;

class ResponseClassProvider
{
    public function getResponseClass(string $content, string $type = 'xml'): string
    {
        $matchCount = preg_match('|<([A-Za-z0-9]*\:)?([A-Za-z0-9]*Response)|', $content, $match);
        
        if ($matchCount < 1) {
            return Response::class;
        }
        
        $responseRoot = $match[count($match)-1];
        
        switch ($responseRoot) {
            case 'TokenExchangeResponse':
                return Response\TokenExchangeResponse::class;

            case 'QueryTaxpayerResponse':
                return Response\QueryTaxpayerResponse::class;

            case 'ManageInvoiceResponse':
                return Response\ManageInvoiceResponse::class;

            case 'GeneralErrorResponse':
                return Response\GeneralErrorResponse::class;

            case 'GeneralExceptionResponse':
                return Response\GeneralExceptionResponse::class;

            case 'QueryTransactionStatusResponse':
                return Response\QueryTransactionStatusResponse::class;
        }
        
        throw new \Exception('unsupported response class: '. $responseRoot);
    }
}

// QueryServiceMetricsResponse
// QueryInvoiceChainDigestResponse
// QueryInvoiceCheckResponse
// GeneralExceptionResponse
// GeneralErrorResponse
// ManageAnnulmentResponse
// ManageInvoiceResponse
// QueryInvoiceChainDigestResponse
// QueryInvoiceCheckResponse
// QueryInvoiceDataResponse
// QueryInvoiceDigestResponse
// QueryTaxpayerResponse
// QueryTransactionListResponse
// QueryTransactionStatusResponse
// TokenExchangeResponse
