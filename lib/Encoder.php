<?php

namespace Nav;

class Encoder
{
    private $key;
    
    public function __construct($key)
    {
        $this->key = $key;
    }
    
    public function encode($string)
    {
        throw new \Exception('unimplemented');
    }

    public function decode($string)
    {
        return openssl_decrypt($string, 'AES-128-ECB', $this->key);
    }
}
