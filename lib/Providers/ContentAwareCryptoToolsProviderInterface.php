<?php

namespace NAV\OnlineInvoice\Providers;

use NAV\OnlineInvoice\Http\Request;

/**
 * A crypto tools provider that receives the content to be signed.
 *
 * For manageInvoice and manageAnnulment the requestSignature also covers the
 * index hashes of the operations (NAV Online Invoice interface specification
 * 3.0, 1.5.1). RequestNormalizer passes them as $content, one string per
 * index: the operation literal concatenated with its base64 data.
 *
 * Will be merged into CryptoToolsProviderInterface in the next major version.
 */
interface ContentAwareCryptoToolsProviderInterface extends CryptoToolsProviderInterface
{
    /**
     * @param iterable<string>|null $content the content to be signed, null for requests without signable content
     */
    public function signRequest(Request $request, ?iterable $content = null): string;
}
