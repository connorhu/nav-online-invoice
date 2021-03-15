<?php

namespace NAV\Tests\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Providers\CryptoToolsProvider;
use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\Header;
use NAV\OnlineInvoice\Http\Request\Software;
use NAV\OnlineInvoice\Http\Request\QueryTaxpayerRequest;
use NAV\OnlineInvoice\Http\Request\User;
use NAV\OnlineInvoice\Serializer\Encoder\RequestEncoder;
use NAV\OnlineInvoice\Serializer\Normalizers\RequestNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\HeaderNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\QueryTaxpayerRequestNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\UserNormalizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;

class QueryTaxpayerRequestNormalizerTest extends TestCase
{
    public function setUp(): void
    {
        $cryptoToolProvider = new CryptoToolsProvider();
        
        $softwareNormalizer = new SoftwareNormalizer();
        $headerNormalizer = new HeaderNormalizer();
        $userNormalizer = new UserNormalizer($cryptoToolProvider);
        $requestNormalizer = new RequestNormalizer($cryptoToolProvider);
        $tokenExchangeNormalizer = new QueryTaxpayerRequestNormalizer($requestNormalizer);
        $normalizers = [$softwareNormalizer, $requestNormalizer, $tokenExchangeNormalizer, $userNormalizer, $headerNormalizer];
        $encoders = [
            new RequestEncoder(new XmlEncoder()),
        ];
        
        $this->serializer = new Serializer($normalizers, $encoders);

        $requestNormalizer->setSerializer($this->serializer);
        
        $this->user = new User();
        $this->user->setLogin('testuserlogin');
        $this->user->setSignKey('key');
        $this->user->setPassword('abc123');
        $this->user->setTaxNumber('12345678-2-11');
    }
    
    private function getSoftware()
    {
        $software = new Software();
        $software->setId('HU69061864-1234567');
        $software->setName('Nav api test @ php');
        $software->setOperation(Software::OPERATION_ONLINE_SERVICE);
        $software->setMainVersion('v1.0');
        $software->setDevName('v1.0');
        $software->setDevContact('connor at connor dot hu');
        $software->setDevCountryCode('HU');
        $software->setDevTaxNumber('69061864-1-33');
        
        return $software;
    }
    
    private function getHeader(): Header
    {
        $header = new Header();
        $header->setTimestamp(new \DateTime('2020-01-01 12:12:12 CET'));

        return $header;
    }
    
    public function testNormalizeRequest()
    {
        $header = $this->getHeader();
        $software = $this->getSoftware();
        
        $request = new QueryTaxpayerRequest();
        $request->setHeader($header);
        $request->setSoftware($software);
        $request->setRequestId('abc');
        $request->setRequestVersion(Request::REQUEST_VERSION_V30);
        $request->setUser($this->user);
        $request->setTaxNumber('69061864-1-33');
        
        $this->assertSame($this->serializer->normalize($request, 'request'), [
            '@xmlns' => 'http://schemas.nav.gov.hu/OSA/3.0/api',
            '@xmlns:common' => 'http://schemas.nav.gov.hu/NTCA/1.0/common',
            '@root_node_name' => 'QueryTaxpayerRequest',
            'common:header' => [
                'common:requestId' => 'abc',
                'common:timestamp' => '2020-01-01T11:12:12.000Z',
                'common:requestVersion' => '3.0',
                'common:headerVersion' => '1.0',
            ],
            'common:user' => [
                'common:login' => 'testuserlogin',
                'common:passwordHash' => [
                    '@cryptoType' => 'SHA-512',
                    '#' => 'C70B5DD9EBFB6F51D09D4132B7170C9D20750A7852F00680F65658F0310E810056E6763C34C9A00B0E940076F54495C169FC2302CCEB312039271C43469507DC',
                ],
                'common:taxNumber' => '12345678',
                'common:requestSignature' => [
                    '@cryptoType' => 'SHA3-512',
                    '#' => 'B9EE2DB14E12C271551548C38B6E21224D1BBC1C06BCDE23DC1D0D61E29AC97BFE5F149E2DF48C73BD5D4CB1AA77B6C6ED9EF2E0D97AD72377A850F451334A41'
                ],
            ],
            'software' => [
                'softwareId' => 'HU69061864-1234567',
                'softwareName' => 'Nav api test @ php',
                'softwareOperation' => 'ONLINE_SERVICE',
                'softwareMainVersion' => 'v1.0',
                'softwareDevName' => 'v1.0',
                'softwareDevContact' => 'connor at connor dot hu',
                'softwareDevCountryCode' => 'HU',
                'softwareDevTaxNumber' => '69061864-1-33',
            ],
            'taxNumber' => '69061864',
        ]);
    }

    public function testSerializeRequest()
    {
        $header = $this->getHeader();
        $software = $this->getSoftware();
        
        $request = new QueryTaxpayerRequest();
        $request->setHeader($header);
        $request->setSoftware($software);
        $request->setRequestId('abc');
        $request->setRequestVersion(Request::REQUEST_VERSION_V30);
        $request->setUser($this->user);
        $request->setTaxNumber('69061864-1-33');
        
        $options = [
            XmlEncoder::FORMAT_OUTPUT => true,
        ];
        
        $output = <<<EOS
<?xml version="1.0"?>
<QueryTaxpayerRequest xmlns="http://schemas.nav.gov.hu/OSA/3.0/api" xmlns:common="http://schemas.nav.gov.hu/NTCA/1.0/common">
  <common:header>
    <common:requestId>abc</common:requestId>
    <common:timestamp>2020-01-01T11:12:12.000Z</common:timestamp>
    <common:requestVersion>3.0</common:requestVersion>
    <common:headerVersion>1.0</common:headerVersion>
  </common:header>
  <common:user>
    <common:login>testuserlogin</common:login>
    <common:passwordHash cryptoType="SHA-512">C70B5DD9EBFB6F51D09D4132B7170C9D20750A7852F00680F65658F0310E810056E6763C34C9A00B0E940076F54495C169FC2302CCEB312039271C43469507DC</common:passwordHash>
    <common:taxNumber>12345678</common:taxNumber>
    <common:requestSignature cryptoType="SHA3-512">B9EE2DB14E12C271551548C38B6E21224D1BBC1C06BCDE23DC1D0D61E29AC97BFE5F149E2DF48C73BD5D4CB1AA77B6C6ED9EF2E0D97AD72377A850F451334A41</common:requestSignature>
  </common:user>
  <software>
    <softwareId>HU69061864-1234567</softwareId>
    <softwareName>Nav api test @ php</softwareName>
    <softwareOperation>ONLINE_SERVICE</softwareOperation>
    <softwareMainVersion>v1.0</softwareMainVersion>
    <softwareDevName>v1.0</softwareDevName>
    <softwareDevContact>connor at connor dot hu</softwareDevContact>
    <softwareDevCountryCode>HU</softwareDevCountryCode>
    <softwareDevTaxNumber>69061864-1-33</softwareDevTaxNumber>
  </software>
  <taxNumber>69061864</taxNumber>
</QueryTaxpayerRequest>

EOS;
        
        $this->assertSame($this->serializer->serialize($request, 'request', $options), $output);
    }
    
    public function testEndpoint()
    {
        $request = new QueryTaxpayerRequest();
        $request->setRequestVersion(Request::REQUEST_VERSION_V30);
        $this->assertSame($request->getEndpointPath(), '/queryTaxpayer');
    }
}
