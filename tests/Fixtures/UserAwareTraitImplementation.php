<?php

namespace NAV\Tests\OnlineInvoice\Fixtures;

use NAV\OnlineInvoice\Http\Request;

class UserAwareTraitImplementation implements Request\UserAwareRequest
{
    use Request\UserAwareTrait;
}
