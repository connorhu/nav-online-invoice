<?php

namespace NAV\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request\InvoiceNumberQueryRequest;

/**
 * Builds the invoiceNumberQuery element (InvoiceNumberQueryType) shared by
 * QueryInvoiceDataRequest and QueryInvoiceCheckRequest.
 */
class InvoiceNumberQueryNormalizer
{
    public static function normalize(InvoiceNumberQueryRequest $request): array
    {
        $query = [
            'invoiceNumber' => $request->getInvoiceNumber(),
            'invoiceDirection' => $request->getInvoiceDirection(),
        ];

        if ($request->getBatchIndex() !== null) {
            $query['batchIndex'] = $request->getBatchIndex();
        }

        if ($request->getSupplierTaxNumber() !== null) {
            $query['supplierTaxNumber'] = $request->getSupplierTaxNumber();
        }

        return [
            'invoiceNumberQuery' => $query,
        ];
    }
}
