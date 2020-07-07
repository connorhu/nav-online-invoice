<?php

namespace NAV\OnlineInvoice\XML;

use \DateTime;
use \DateTimeZone;
use \DomDocument;

class XMLWriter
{
    private $dom;
    public $root;
    
    public function createRootElementWithName($name, $rootNamespaces = [])
    {
        $this->dom = new DomDocument('1.0', 'utf-8');
        if (isset($rootNamespaces['xmlns'])) {
            $this->root = $this->dom->createElementNS($rootNamespaces['xmlns'], $name);
            foreach ($rootNamespaces as $name => $url) {
                if ($name === 'xmlns') {
                    continue;
                }

                $this->root->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:'. $name, 'http://www.w3.org/2001/XMLSchema-instance');
                $this->root->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'schemaLocation', $url);
            }
        }
        else {
            $this->root = $this->dom->createElement($name);
        }
        
        $this->dom->appendChild($this->root);
    }

    public function addElement($node)
    {
        $this->root->appendChild($node);
    }

    public function createElement($tag, $value = null, $attributes = [], $childs = [])
    {
        $element = $this->dom->createElement($tag, htmlspecialchars($value));
        
        foreach ($childs as $child) {
            $element->appendChild($child);
        }

        foreach ($attributes as $name => $value) {
            $element->setAttribute($name, $value);
        }
        
        return $element;
    }
    
    public function getXmlString()
    {
        if ($this->dom === null) {
            throw new Exceptions\LogicException('init root first');
        }
        
        return $this->dom->saveXml();
    }
}
