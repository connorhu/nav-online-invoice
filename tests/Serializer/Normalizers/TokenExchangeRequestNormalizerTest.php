<?php

namespace NAV\OnlineInvoice\Tests\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Enums\RequestVersionEnum;
use NAV\OnlineInvoice\Tests\Fixtures\CryptoToolsProvider;
use NAV\OnlineInvoice\Http\Request;
use NAV\OnlineInvoice\Http\Request\Header;
use NAV\OnlineInvoice\Http\Request\Software;
use NAV\OnlineInvoice\Http\Request\TokenExchangeRequest;
use NAV\OnlineInvoice\Http\Request\User;
use NAV\OnlineInvoice\Serializer\Encoder\RequestEncoder;
use NAV\OnlineInvoice\Serializer\Normalizers\RequestNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\HeaderNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\TokenExchangeRequestNormalizer;
use NAV\OnlineInvoice\Serializer\Normalizers\UserNormalizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;

class TokenExchangeRequestNormalizerTest extends TestCase
{
    private Serializer $serializer;
    private User $user;

    public function setUp(): void
    {
        $cryptoToolProvider = new CryptoToolsProvider();
        
        $softwareNormalizer = new SoftwareNormalizer();
        $headerNormalizer = new HeaderNormalizer();
        $userNormalizer = new UserNormalizer($cryptoToolProvider);
        $requestNormalizer = new RequestNormalizer($cryptoToolProvider);
        $tokenExchangeNormalizer = new TokenExchangeRequestNormalizer($requestNormalizer);
        $normalizers = [$softwareNormalizer, $requestNormalizer, $tokenExchangeNormalizer, $userNormalizer, $headerNormalizer];
        $encoders = [
            new RequestEncoder(),
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
        $header->setTimestamp(new \DateTimeImmutable('2020-01-01 12:12:12 CET'));

        return $header;
    }
    
    public function testNormalizeRequest()
    {
        $header = $this->getHeader();
        $software = $this->getSoftware();
        
        $request = new TokenExchangeRequest();
        $request->setHeader($header);
        $request->setSoftware($software);
        $request->setRequestId('abc');
        $request->setRequestVersion(RequestVersionEnum::v30);
        $request->setUser($this->user);
        
        $this->assertSame($this->serializer->normalize($request, 'request'), [
            '@xmlns' => 'http://schemas.nav.gov.hu/OSA/3.0/api',
            '@xmlns:common' => 'http://schemas.nav.gov.hu/NTCA/1.0/common',
            '@root_node_name' => 'TokenExchangeRequest',
            'common:header' => [
                'common:requestId' => 'abc',
                'common:timestamp' => '2020-01-01T12:12:12.000Z',
                'common:requestVersion' => '3.0',
                'common:headerVersion' => '1.0',
            ],
            'common:user' => [
                'common:login' => 'testuserlogin',
                'common:passwordHash' => [
                    '@cryptoType' => 'SHA-512',
                    '#' => 'user-password-hash',
                ],
                'common:taxNumber' => '12345678',
                'common:requestSignature' => [
                    '@cryptoType' => 'SHA3-512',
                    '#' => 'request-signature'
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
        ]);
    }
    
    public function testSerializeRequest()
    {
        $header = $this->getHeader();
        $software = $this->getSoftware();
        
        $request = new TokenExchangeRequest();
        $request->setHeader($header);
        $request->setSoftware($software);
        $request->setRequestId('abc');
        $request->setRequestVersion(RequestVersionEnum::v30);
        $request->setUser($this->user);
        
        $options = [
            XmlEncoder::FORMAT_OUTPUT => true,
        ];

        $output = <<<EOS
<?xml version="1.0"?>
<TokenExchangeRequest xmlns="http://schemas.nav.gov.hu/OSA/3.0/api" xmlns:common="http://schemas.nav.gov.hu/NTCA/1.0/common">
  <common:header>
    <common:requestId>abc</common:requestId>
    <common:timestamp>2020-01-01T12:12:12.000Z</common:timestamp>
    <common:requestVersion>3.0</common:requestVersion>
    <common:headerVersion>1.0</common:headerVersion>
  </common:header>
  <common:user>
    <common:login>testuserlogin</common:login>
    <common:passwordHash cryptoType="SHA-512">user-password-hash</common:passwordHash>
    <common:taxNumber>12345678</common:taxNumber>
    <common:requestSignature cryptoType="SHA3-512">request-signature</common:requestSignature>
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
</TokenExchangeRequest>

EOS;
        
        $this->assertSame($this->serializer->serialize($request, 'request', $options), $output);
    }
    
    public function testEndpoint()
    {
        $request = new TokenExchangeRequest();
        $request->setRequestVersion(RequestVersionEnum::v30);
        $this->assertSame($request->getEndpointPath(), '/tokenExchange');
    }
}
