<?php

namespace NAV\OnlineInvoice\Http\Request;


use NAV\OnlineInvoice\Http\Enums\HeaderVersionEnum;

class Header
{
    protected ?HeaderAwareRequest $request = null;

    protected HeaderVersionEnum $headerVersion = HeaderVersionEnum::V1;

    protected \DateTimeImmutable $timestamp;

    /**
     * @return HeaderAwareRequest
     */
    public function getRequest(): HeaderAwareRequest
    {
        return $this->request;
    }

    /**
     * @param HeaderAwareRequest $request
     * @return self
     */
    public function setRequest(HeaderAwareRequest $request): self
    {
        if ($this->request !== $request) {
            $this->request = $request;
        }
        
        return $this;
    }

    /**
     * @return HeaderVersionEnum
     */
    public function getHeaderVersion(): HeaderVersionEnum
    {
        return $this->headerVersion;
    }

    /**
     * @param HeaderVersionEnum $headerVersion
     * @return self
     */
    public function setHeaderVersion(HeaderVersionEnum $headerVersion): self
    {
        $this->headerVersion = $headerVersion;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getTimestamp(): \DateTimeImmutable
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTimeImmutable $timestamp
     * @return self
     */
    public function setTimestamp(\DateTimeImmutable $timestamp): self
    {
        if ($timestamp->getTimezone()->getName() !== 'UTC') {
            $timestamp->setTimezone(new \DateTimeZone('UTC'));
        }

        $this->timestamp = $timestamp;
        return $this;
    }
}
