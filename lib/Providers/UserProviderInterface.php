<?php

namespace NAV\OnlineInvoice\Providers;

use NAV\OnlineInvoice\Http\Request\User;

interface UserProviderInterface
{
    public function getUser(): User;
}