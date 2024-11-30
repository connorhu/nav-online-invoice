<?php

namespace NAV\OnlineInvoice\Http\Enums;

enum RequestVersionEnum: string
{
    case v10 = '1.0';
    case v11 = '1.1';
    case v20 = '2.0';
    case v30 = '3.0';
}
