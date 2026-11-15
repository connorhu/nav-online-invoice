<?php

namespace NAV\OnlineInvoice\Tests\Fixtures;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Providers\ContentAwareCryptoToolsProviderInterface;

class ContentAwareCryptoToolsProvider extends CryptoToolsProvider implements ContentAwareCryptoToolsProviderInterface
{
    /**
     * @var list<array<string>|null> the $content of each signRequest() call
     */
    public array $signedContents = [];

    public function signRequest(Request $request, ?iterable $content = null): string
    {
        $this->signedContents[] = $content === null ? null : [...$content];

        return 'request-signature';
    }
}
