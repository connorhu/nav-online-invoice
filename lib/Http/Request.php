<?php

namespace NAV\OnlineInvoice\Http;

use NAV\OnlineInvoice\Http\Enums\RequestVersionEnum;

abstract class Request
{
    const REQUEST_VERSION_V10 = '1.0';
    const REQUEST_VERSION_V11 = '1.1';
    const REQUEST_VERSION_V20 = '2.0';
    const REQUEST_VERSION_V30 = '3.0';

    /**
     * @var RequestVersionEnum
     */
    protected RequestVersionEnum $requestVersion = RequestVersionEnum::v30;

    abstract public function getEndpointPath(): string;
    abstract public function getServiceKind(): RequestServiceKindEnum;

    /**
     * @return RequestVersionEnum
     */
    public function getRequestVersion(): RequestVersionEnum
    {
        return $this->requestVersion;
    }

    /**
     * @param RequestVersionEnum $requestVersion
     * @return self
     */
    public function setRequestVersion(RequestVersionEnum $requestVersion): self
    {
        $this->requestVersion = $requestVersion;

        return $this;
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
