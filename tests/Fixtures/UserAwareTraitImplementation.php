<?php

namespace NAV\OnlineInvoice\Tests\Fixtures;

use NAV\OnlineInvoice\Http\Request;

class UserAwareTraitImplementation implements Request\UserAwareRequest
{
    use Request\UserAwareTrait;
}
