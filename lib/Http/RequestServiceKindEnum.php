<?php

namespace NAV\OnlineInvoice\Http;

enum RequestServiceKindEnum: string
{
    case MetricService = 'metricService';
    case InvoiceService = 'invoiceService';
}
