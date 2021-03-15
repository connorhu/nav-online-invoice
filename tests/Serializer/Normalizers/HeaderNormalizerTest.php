<?php

namespace NAV\Tests\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\Header;
use NAV\OnlineInvoice\Serializer\Normalizers\HeaderNormalizer;
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
        $header->getRequest()->setRequestVersion(Request::REQUEST_VERSION_V30);
        $header->setTimestamp(new \DateTime('2020-01-01 11:00:00 UTC'));
        
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
        $header->getRequest()->setRequestVersion(Request::REQUEST_VERSION_V30);
        $header->setTimestamp(new \DateTime('2020-01-01 11:00:00 CET'));
        
        $normalizer = new HeaderNormalizer();

        $this->assertSame($normalizer->normalize($header), [
            'common:requestId' => 'abc123',
            'common:timestamp' => '2020-01-01T10:00:00.000Z',
            'common:requestVersion' => '3.0',
            'common:headerVersion' => '1.0',
        ]);
    }
    
    public function testNonV3Namespace()
    {
        $header = $this->getEmptyHeader();

        $header->getRequest()->setRequestId('abc123');
        $header->getRequest()->setRequestVersion(Request::REQUEST_VERSION_V20);
        $header->setTimestamp(new \DateTime('2020-01-01 11:00:00 UTC'));
        
        $normalizer = new HeaderNormalizer();

        $this->assertSame($normalizer->normalize($header), [
            'requestId' => 'abc123',
            'timestamp' => '2020-01-01T11:00:00.000Z',
            'requestVersion' => '2.0',
            'headerVersion' => '1.0',
        ]);
    }
}
