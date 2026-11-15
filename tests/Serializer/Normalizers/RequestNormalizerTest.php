<?php

namespace NAV\OnlineInvoice\Tests\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Enums\RequestVersionEnum;
use NAV\OnlineInvoice\Http\Request\Header;
use NAV\OnlineInvoice\Http\Request\ManageInvoiceRequest;
use NAV\OnlineInvoice\Http\Request\Software;
use NAV\OnlineInvoice\Http\Request\TokenExchangeRequest;
use NAV\OnlineInvoice\Http\Request\User;
use NAV\OnlineInvoice\Providers\CompactDataProvider;
use NAV\OnlineInvoice\Providers\ContentAwareCryptoToolsProviderInterface;
use NAV\OnlineInvoice\Providers\CryptoToolsProviderInterface;
use NAV\OnlineInvoice\Serializer\Normalizers\HeaderNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\RequestNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\UserNormalizer;
use NAV\OnlineInvoice\Tests\Fixtures\ContentAwareCryptoToolsProvider;
use NAV\OnlineInvoice\Tests\Fixtures\CryptoToolsProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Serializer;

#[CoversClass(RequestNormalizer::class)]
class RequestNormalizerTest extends TestCase
{
    private const REQUEST_CONTENT = [
        'invoiceOperations' => [
            'compressedContent' => 'false',
            'invoiceOperation' => [
                ['index' => 1, 'invoiceOperation' => 'CREATE', 'invoiceData' => 'PElOVk9JQ0UxLz4='],
                ['index' => 2, 'invoiceOperation' => 'MODIFY', 'invoiceData' => 'PElOVk9JQ0UyLz4='],
            ],
        ],
    ];

    private static function createNormalizer(CryptoToolsProviderInterface $cryptoTools): RequestNormalizer
    {
        $requestNormalizer = new RequestNormalizer($cryptoTools);
        $serializer = new Serializer([
            new SoftwareNormalizer(),
            new HeaderNormalizer(),
            new UserNormalizer($cryptoTools),
        ]);
        $requestNormalizer->setSerializer($serializer);

        return $requestNormalizer;
    }

    private static function createRequest(string $class): ManageInvoiceRequest|TokenExchangeRequest
    {
        $request = new $class();
        $request->setRequestVersion(RequestVersionEnum::v30);
        $request->setRequestId('RID1');
        $request->setHeader((new Header())->setTimestamp(new \DateTimeImmutable('2020-01-01 10:00:00 UTC')));

        $user = new User();
        $user->setLogin('login');
        $user->setPassword('password');
        $user->setTaxNumber('12345678');
        $user->setSignKey('key');
        $request->setUser($user);

        $software = new Software();
        $software->setId('HU12345678-TEST01');
        $software->setName('Test');
        $software->setOperation(Software::OPERATION_ONLINE_SERVICE);
        $software->setMainVersion('1.0');
        $software->setDevName('Developer');
        $software->setDevContact('dev@example.com');
        $software->setDevCountryCode('HU');
        $software->setDevTaxNumber('12345678');
        $request->setSoftware($software);

        return $request;
    }

    /**
     * @return list<string> the messages of the E_USER_DEPRECATED errors raised by $callback
     */
    private static function collectDeprecations(callable $callback): array
    {
        $deprecations = [];
        set_error_handler(static function (int $type, string $message) use (&$deprecations): bool {
            $deprecations[] = $message;

            return true;
        }, E_USER_DEPRECATED);

        try {
            $callback();
        } finally {
            restore_error_handler();
        }

        return $deprecations;
    }

    public function testContentAwareProviderReceivesTheContentToSign(): void
    {
        $cryptoTools = new ContentAwareCryptoToolsProvider();
        $normalizer = self::createNormalizer($cryptoTools);

        $deprecations = self::collectDeprecations(fn () => $normalizer->normalize(self::createRequest(ManageInvoiceRequest::class), 'request', [
            RequestNormalizer::REQUEST_CONTENT_KEY => self::REQUEST_CONTENT,
        ]));

        $this->assertSame([['CREATEPElOVk9JQ0UxLz4=', 'MODIFYPElOVk9JQ0UyLz4=']], $cryptoTools->signedContents);
        $this->assertSame([], $deprecations);
    }

    public function testContentAwareProviderGetsNoContentForOtherRequests(): void
    {
        $cryptoTools = new ContentAwareCryptoToolsProvider();

        self::createNormalizer($cryptoTools)->normalize(self::createRequest(TokenExchangeRequest::class), 'request');

        $this->assertSame([null], $cryptoTools->signedContents);
    }

    public function testProviderWithoutContentSupportTriggersDeprecationForSignableContent(): void
    {
        $normalizer = self::createNormalizer(new CryptoToolsProvider());

        $normalized = null;
        $deprecations = self::collectDeprecations(function () use ($normalizer, &$normalized) {
            $normalized = $normalizer->normalize(self::createRequest(ManageInvoiceRequest::class), 'request', [
                RequestNormalizer::REQUEST_CONTENT_KEY => self::REQUEST_CONTENT,
            ]);
        });

        $this->assertCount(1, $deprecations);
        $this->assertStringContainsString(ContentAwareCryptoToolsProviderInterface::class, $deprecations[0]);
        // the current behaviour is kept
        $this->assertSame('request-signature', $normalized['common:user']['common:requestSignature']['#']);
    }

    public function testProviderWithoutContentSupportIsFineForOtherRequests(): void
    {
        $normalizer = self::createNormalizer(new CryptoToolsProvider());

        $deprecations = self::collectDeprecations(fn () => $normalizer->normalize(self::createRequest(TokenExchangeRequest::class), 'request'));

        $this->assertSame([], $deprecations);
    }

    public function testCompactDataProviderIsContentAware(): void
    {
        $this->assertTrue(is_subclass_of(CompactDataProvider::class, ContentAwareCryptoToolsProviderInterface::class));
    }
}
