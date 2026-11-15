<?php

namespace NAV\OnlineInvoice\Tests\Providers;

use NAV\OnlineInvoice\Http\Enums\RequestVersionEnum;
use NAV\OnlineInvoice\Http\Request\Header;
use NAV\OnlineInvoice\Http\Request\TokenExchangeRequest;
use NAV\OnlineInvoice\Providers\CompactDataProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CompactDataProvider::class)]
class CompactDataProviderTest extends TestCase
{
    /**
     * Spec 1.5.2: requestId + UTC timestamp (YYYYMMDDhhmmss) + signature key, SHA3-512, uppercase.
     */
    public function testSignatureUsesTheUtcTimestamp(): void
    {
        $provider = new CompactDataProvider(__DIR__.'/../Fixtures/compact_data_provider_info.json');

        $request = new TokenExchangeRequest();
        $request->setRequestVersion(RequestVersionEnum::v30);
        $request->setRequestId('RID123');
        $request->setUser($provider->getUser());

        $header = new Header();
        $header->setTimestamp(new \DateTimeImmutable('2020-07-01 12:00:00', new \DateTimeZone('Europe/Budapest')));
        $request->setHeader($header);

        $expected = strtoupper(hash('sha3-512', 'RID123'.'20200701100000'.'sign-key'));

        $this->assertSame($expected, $provider->signRequest($request));
    }
}
