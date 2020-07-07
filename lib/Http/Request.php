<?php

namespace NAV\OnlineInvoice\Http;

use NAV\OnlineInvoice\Http\Request\Software;
use NAV\OnlineInvoice\Http\Request\Header;
use NAV\OnlineInvoice\Http\Request\User;
use Symfony\Component\Validator\Constraints as Assert;

abstract class Request
{
    /**
     * @Assert\NotBlank(groups="v2.0")
     */
    protected $software;
    
    /**
     * @Assert\NotBlank(groups="v2.0")
     */
    protected $requestId;
    
    /**
     * @Assert\NotBlank(groups="v2.0")
     */
    protected $user;
    
    protected $rootObjectName;
    
    /**
     * @Assert\NotBlank(groups="v2.0")
     */
    protected $header;
    
    public function __construct()
    {
        $this->software;
    }
    
    /**
     * setter for software
     *
     * @param mixed 
     * @return self
     */
    public function setSoftware(Software $value)
    {
        $this->software = $value;
        return $this;
    }
    
    /**
     * getter for software
     * 
     * @return mixed return value for 
     */
    public function getSoftware(): Software
    {
        return $this->software;
    }
    
    /**
     * setter for requestId
     *
     * @param mixed 
     * @return self
     */
    public function setRequestId(string $value)
    {
        $this->requestId = $value;
        return $this;
    }
    
    /**
     * getter for requestId
     * 
     * @return mixed return value for 
     */
    public function getRequestId(): string
    {
        return $this->requestId;
    }
    
    /**
     * setter for user
     *
     * @param mixed 
     * @return self
     */
    public function setUser(User $value)
    {
        $this->user = $value;
        return $this;
    }
    
    /**
     * getter for user
     * 
     * @return mixed return value for User
     */
    public function getUser(): User
    {
        return $this->user;
    }
    
    abstract public function getRootNodeName();
    abstract public function getEndpointPath();
    
    /**
     * setter for rootObjectName
     *
     * @param mixed 
     * @return self
     */
    public function setRootObjectName($value)
    {
        $this->rootObjectName = $value;
        return $this;
    }
    
    /**
     * getter for rootObjectName
     * 
     * @return mixed return value for 
     */
    public function getRootObjectName()
    {
        return $this->rootObjectName;
    }
    
    
    /**
     * setter for header
     *
     * @param mixed 
     * @return self
     */
    public function setHeader(Header $value)
    {
        $this->header = $value;
        return $this;
    }
    
    /**
     * getter for header
     * 
     * @return mixed return value for 
     */
    public function getHeader()
    {
        return $this->header;
    }
}