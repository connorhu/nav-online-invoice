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
}
