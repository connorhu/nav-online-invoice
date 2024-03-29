<?php

namespace NAV\OnlineInvoice\Providers;


interface RequestIdProviderInterface
{
    public function getRequestId(): string;
}