<?php

namespace NAV\Tests\OnlineInvoice\Serializer\Normalizers;

use NAV\OnlineInvoice\Http\Request\Software;
use NAV\OnlineInvoice\Serializer\Normalizers\SoftwareNormalizer;
use PHPUnit\Framework\TestCase;

class SoftwareNormalizerTest extends TestCase
{
    public function testNormalizeOnAllFields()
    {
        $software = new Software();
        
        $software->setId('HU6906186400000000');
        $software->setName('NAVPHP-API');
        $software->setOperation(Software::OPERATION_ONLINE_SERVICE);
        $software->setMainVersion('v1.0');
        $software->setDevName('Karoly Gossler');
        $software->setDevContact('gkaroly at gmail dot hu');
        $software->setDevCountryCode('HU');
        $software->setDevTaxNumber('69061864-1-33');
        
        $normalizer = new SoftwareNormalizer();
        
        $this->assertSame($normalizer->normalize($software), [
            'softwareId' => 'HU6906186400000000',
            'softwareName' => 'NAVPHP-API',
            'softwareOperation' => 'ONLINE_SERVICE',
            'softwareMainVersion' => 'v1.0',
            'softwareDevName' => 'Karoly Gossler',
            'softwareDevContact' => 'gkaroly at gmail dot hu',
            'softwareDevCountryCode' => 'HU',
            'softwareDevTaxNumber' => '69061864-1-33',
        ]);
    }
    
    public function testWithoutOptionalFields()
    {
        $software = new Software();
        
        $software->setId('HU6906186400000000');
        $software->setName('NAVPHP-API');
        $software->setOperation(Software::OPERATION_ONLINE_SERVICE);
        $software->setMainVersion('v1.0');
        $software->setDevName('Karoly Gossler');
        $software->setDevContact('gkaroly at gmail dot hu');
        
        $normalizer = new SoftwareNormalizer();
        
        $this->assertSame($normalizer->normalize($software), [
            'softwareId' => 'HU6906186400000000',
            'softwareName' => 'NAVPHP-API',
            'softwareOperation' => 'ONLINE_SERVICE',
            'softwareMainVersion' => 'v1.0',
            'softwareDevName' => 'Karoly Gossler',
            'softwareDevContact' => 'gkaroly at gmail dot hu',
        ]);
    }
    
    public function testCountryCodeField()
    {
        $software = new Software();
        
        $software->setId('HU6906186400000000');
        $software->setName('NAVPHP-API');
        $software->setOperation(Software::OPERATION_ONLINE_SERVICE);
        $software->setMainVersion('v1.0');
        $software->setDevName('Karoly Gossler');
        $software->setDevContact('gkaroly at gmail dot hu');
        $software->setDevCountryCode('HU');
        
        $normalizer = new SoftwareNormalizer();
        
        $this->assertSame($normalizer->normalize($software), [
            'softwareId' => 'HU6906186400000000',
            'softwareName' => 'NAVPHP-API',
            'softwareOperation' => 'ONLINE_SERVICE',
            'softwareMainVersion' => 'v1.0',
            'softwareDevName' => 'Karoly Gossler',
            'softwareDevContact' => 'gkaroly at gmail dot hu',
            'softwareDevCountryCode' => 'HU',
        ]);
    }
    
    public function testDevTaxNumberField()
    {
        $software = new Software();
        
        $software->setId('HU6906186400000000');
        $software->setName('NAVPHP-API');
        $software->setOperation(Software::OPERATION_ONLINE_SERVICE);
        $software->setMainVersion('v1.0');
        $software->setDevName('Karoly Gossler');
        $software->setDevContact('gkaroly at gmail dot hu');
        $software->setDevTaxNumber('69061864-1-33');
        
        $normalizer = new SoftwareNormalizer();
        
        $this->assertSame($normalizer->normalize($software), [
            'softwareId' => 'HU6906186400000000',
            'softwareName' => 'NAVPHP-API',
            'softwareOperation' => 'ONLINE_SERVICE',
            'softwareMainVersion' => 'v1.0',
            'softwareDevName' => 'Karoly Gossler',
            'softwareDevContact' => 'gkaroly at gmail dot hu',
            'softwareDevTaxNumber' => '69061864-1-33',
        ]);
    }
    
    public function testUnsetRequiredFields()
    {
        $this->expectException(\TypeError::class);

        $software = new Software();

        $normalizer = new SoftwareNormalizer();

        $normalizer->normalize($software);
    }

    public function testDenormalize()
    {
        $content = [
            'softwareId' => 'SOFTWAREID',
            'softwareName' => 'Software Name',
            'softwareOperation' => 'ONLINE_SERVICE',
            'softwareMainVersion' => '1.0',
            'softwareDevName' => 'Developer Kft.',
            'softwareDevContact' => 'info@developer.tld',
            'softwareDevCountryCode' => 'HU',
            'softwareDevTaxNumber' => '12345678-9-11',
        ];

        $normalizer = new SoftwareNormalizer();
        $denormalized = $normalizer->denormalize($content, Software::class, 'xml', [
            SoftwareNormalizer::XMLNS_CONTEXT_KEY => null,
        ]);

        $this->assertSame('SOFTWAREID', $denormalized->getId());
        $this->assertSame('Software Name', $denormalized->getName());
        $this->assertSame('ONLINE_SERVICE', $denormalized->getOperation());
        $this->assertSame('1.0', $denormalized->getMainVersion());
        $this->assertSame('Developer Kft.', $denormalized->getDevName());
        $this->assertSame('info@developer.tld', $denormalized->getDevContact());
        $this->assertSame('HU', $denormalized->getDevCountryCode());
        $this->assertSame('12345678-9-11', $denormalized->getDevTaxNumber());
    }
}
