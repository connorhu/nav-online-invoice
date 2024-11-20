<?php

namespace NAV\OnlineInvoice\Http;

abstract class Request
{
    const REQUEST_VERSION_V10 = '1.0';
    const REQUEST_VERSION_V11 = '1.1';
    const REQUEST_VERSION_V20 = '2.0';
    const REQUEST_VERSION_V30 = '3.0';

    abstract public function getEndpointPath(): string;
    abstract public function getServiceKind(): RequestServiceKindEnum;

    /**
     * @var string
     */
    protected string $requestVersion = self::REQUEST_VERSION_V11;
    
    /**
     * @param string $requestVersion One of self::REQUEST_VERSION_*
     * @return self
     */
    public function setRequestVersion(string $requestVersion): self
    {
        $this->requestVersion = $requestVersion;

        return $this;
    }
    
    /**
     * @return string One of self::REQUEST_VERSION_*
     */
    public function getRequestVersion(): string
    {
        return $this->requestVersion;
    }
    
    // TODO [+a-zA-Z0-9_]{1,30}
    private string $requestId;
    
    /**
     * @param string $requestId uniq id for request
     * @return self
     */
    public function setRequestId(string $requestId): self
    {
        $this->requestId = $requestId;
        
        return $this;
    }
    
    /**
     * @return string uniq id for request
     */
    public function getRequestId(): string
    {
        return $this->requestId;
    }
}
