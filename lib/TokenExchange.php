<?php

namespace Nav;

use NAV\OnlineInvoice\Http\Request\TokenExchangeRequest;

class TokenExchange
{
    private $client;
    private $encoder;
    
    public function __construct(NavRestClient $client, Encoder $encoder)
    {
        $this->client = $client;
        $this->encoder = $encoder;
    }
    
    public function fetchToken()
    {
        $request = new TokenExchangeRequest();
        
        $response = $this->client->sendRequest($request);
        
        return $response->getToken($this->encoder);
    }
}
