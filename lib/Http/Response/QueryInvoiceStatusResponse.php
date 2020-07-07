<?php

namespace NAV\OnlineInvoice\Http\Response;

use NAV\OnlineInvoice\Token\RequestToken;
use NAV\OnlineInvoice\Http\Response;

class QueryInvoiceStatusResponse extends Response
{
    public function getResults()
    {
        $results = [];
        foreach ($this->xml->processingResults->processingResult as $processingResult) {
            $result = [
                'index' => (string)$processingResult->index,
                'invoiceStatus' => (string)$processingResult->invoiceStatus,
                'schemaValidationMessages' => [],
                'businessValidationMessages' => [],
                'technicalValidationMessages' => [],
            ];
            
            if (isset($processingResult->schemaValidationMessages)) {
                foreach ($processingResult->schemaValidationMessages as $messages) {
                    $result['schemaValidationMessages'][] = [
                        'code' => (string) $messages->validationResultCode,
                        'message' => (string) $messages->message,
                    ];
                }
            }

            if (isset($processingResult->businessValidationMessages)) {
                foreach ($processingResult->businessValidationMessages as $messages) {
                    $result['businessValidationMessages'][] = [
                        'code' => (string) $messages->validationResultCode,
                        'error_code' => (string) $messages->validationErrorCode,
                        'message' => (string) $messages->message,
                    ];
                }
            }

            if (isset($processingResult->technicalValidationMessages)) {
                foreach ($processingResult->technicalValidationMessages as $messages) {
                    $result['technicalValidationMessages'][] = [
                        'code' => (string) $messages->validationResultCode,
                        'error_code' => (string) $messages->validationErrorCode,
                        'message' => (string) $messages->message,
                    ];
                }
            }
            
            if (isset($processingResult->originalRequest)) {
                $result['originalRequest'] = base64_decode($processingResult->originalRequest);
            }

            $results[] = $result;
        }
        
        return $results;
    }
}