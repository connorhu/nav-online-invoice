<?php

namespace NAV\OnlineInvoice\Serializer\Encoder;

use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class RequestEncoder implements EncoderInterface, DecoderInterface
{
    /**
     * @var XmlEncoder
     */
    private XmlEncoder $xmlEncoder;
    
    public function __construct()
    {
        $this->xmlEncoder = new XmlEncoder();
    }
    
    public function encode($data, string $format, array $context = []): string
    {
        if (!isset($data['@root_node_name'])) {
            throw new \LogicException('Root node name is missing.');
        }
        
        $context['xml_root_node_name'] = $data['@root_node_name'];
        unset($data['@root_node_name']);
        
        return $this->xmlEncoder->encode($data, 'xml', $context);
    }

    public function supportsEncoding(string $format): bool
    {
        return 'request' === $format || 'invoice_xml' === $format;;
    }

    public function decode(string $data, string $format, array $context = []): mixed
    {
        return $this->xmlEncoder->decode($data, 'xml', $context);
    }

    public function supportsDecoding(string $format): bool
    {
        return 'request' === $format;
    }
}
    
