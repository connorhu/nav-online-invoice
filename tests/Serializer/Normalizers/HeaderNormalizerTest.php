<?php

namespace NAV\OnlineInvoice\Tests\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Enums\HeaderVersionEnum;
use NAV\OnlineInvoice\Http\Enums\RequestVersionEnum;
use NAV\OnlineInvoice\Http\Request\Header;
use NAV\OnlineInvoice\Serializer\Normalizers\HeaderNormalizer;
use NAV\OnlineInvoice\Tests\Fixtures\HeaderAwareRequest;
use PHPUnit\Framework\TestCase;

class HeaderNormalizerTest extends TestCase
{
    protected function getEmptyHeader(): Header
    {
        $request = new HeaderAwareRequest();
        $header = new Header();
        $request->setHeader($header);
        
        return $header;
    }
    
    public function testNormalizeOnAllFields()
    {
        $header = $this->getEmptyHeader();

        $header->getRequest()->setRequestId('abc123');
        $header->getRequest()->setRequestVersion(RequestVersionEnum::v30);
        $header->setTimestamp(new \DateTimeImmutable('2020-01-01 11:00:00 UTC'));
        
        $normalizer = new HeaderNormalizer();

        $this->assertSame($normalizer->normalize($header), [
            'common:requestId' => 'abc123',
            'common:timestamp' => '2020-01-01T11:00:00.000Z',
            'common:requestVersion' => '3.0',
            'common:headerVersion' => '1.0',
        ]);
    }
    
    public function testNormalizeNonUTCDate()
    {
        $header = $this->getEmptyHeader();
        $header->getRequest()->setRequestId('abc123');
        $header->getRequest()->setRequestVersion(RequestVersionEnum::v30);
        $header->setTimestamp(new \DateTimeImmutable('2020-01-01 11:00:00 CET'));
        
        $normalizer = new HeaderNormalizer();

        $this->assertSame($normalizer->normalize($header), [
            'common:requestId' => 'abc123',
            'common:timestamp' => '2020-01-01T11:00:00.000Z',
            'common:requestVersion' => '3.0',
            'common:headerVersion' => '1.0',
        ]);
    }
    
    public function testNonV3Namespace()
    {
        $header = $this->getEmptyHeader();

        $header->getRequest()->setRequestId('abc123');
        $header->getRequest()->setRequestVersion(RequestVersionEnum::v20);
        $header->setTimestamp(new \DateTimeImmutable('2020-01-01 11:00:00 UTC'));
        
        $normalizer = new HeaderNormalizer();

        $this->assertSame($normalizer->normalize($header), [
            'requestId' => 'abc123',
            'timestamp' => '2020-01-01T11:00:00.000Z',
            'requestVersion' => '2.0',
            'headerVersion' => '1.0',
        ]);
    }

    public function testDenormalize()
    {
        $content = [
            'ns2:requestId' => 'RANDOMID',
            'ns2:timestamp' => '2023-01-01T10:10:12.000Z',
            'ns2:requestVersion' => '3.0',
            'ns2:headerVersion' => '1.0',
        ];

        $normalizer = new HeaderNormalizer();
        $denormalized = $normalizer->denormalize($content, Header::class, 'xml', [
            HeaderNormalizer::XMLNS_CONTEXT_KEY => 'ns2',
        ]);

        $this->assertSame(HeaderVersionEnum::V1, $denormalized->getHeaderVersion());
        $this->assertSame('1.0', $denormalized->getHeaderVersion()->value);
        $this->assertSame('2023-01-01T10:10:12.000Z', $denormalized->getTimestamp()->format('Y-m-d\TH:i:s.000\Z'));
    }

    // TODO public function testDenormalizeMissingXmlnsContextKey()
}
