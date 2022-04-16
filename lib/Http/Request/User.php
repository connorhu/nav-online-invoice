<?php

namespace NAV\OnlineInvoice\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

class User
{
    protected ?UserAwareRequest $request = null;
    
    /**
     * setter for request
     *
     * @param UserAwareRequest $request
     * @return self
     */
    public function setRequest(UserAwareRequest $request): self
    {
        if ($this->request !== $request) {
            $this->request = $request;
            $this->request->setUser($this);
        }
        
        return $this;
    }
    
    /**
     * getter for request
     * 
     * @return UserAwareRequest|null
     */
    public function getRequest(): ?UserAwareRequest
    {
        return $this->request;
    }
    
    /**
     * @Assert\NotBlank(groups={"v1.0", "v1.1", "v2.0", "v3.0"})
     */
    protected string $login = '';
    
    /**
     * setter for login
     *
     * @param string
     * @return self
     */
    public function setLogin(string $login): self
    {
        $this->login = $login;
        return $this;
    }
    
    /**
     * getter for login
     * 
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }
    
    /**
     * @Assert\NotBlank(groups={"v1.0", "v1.1", "v2.0", "v3.0"})
     */
    protected string $password = '';
    
    /**
     * setter for password
     *
     * @param string $password
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }
    
    /**
     * getter for password
     * 
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
    
    /**
     * @Assert\NotBlank(groups={"v2.0", "v3.0"})
     * @Assert\Length(max=8, min=8, groups={"v1.0", "v1.1", "v2.0", "v3.0"})
     */
    protected string $taxNumber = '';
    
    /**
     * setter for taxNumber
     *
     * @param string $taxNumber
     * @return self
     */
    public function setTaxNumber(string $taxNumber): self
    {
        $this->taxNumber = substr($taxNumber, 0, 8);
        return $this;
    }
    
    /**
     * getter for taxNumber
     * 
     * @return string
     */
    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }
    
    /**
     * @Assert\NotBlank(groups={"v1.0", "v1.1", "v2.0", "v3.0"})
     */
    protected string $signKey;
    
    /**
     * setter for signKey
     *
     * @param string $signKey
     * @return self
     */
    public function setSignKey(string $signKey): self
    {
        $this->signKey = $signKey;
        return $this;
    }
    
    /**
     * getter for signKey
     * 
     * @return string
     */
    public function getSignKey(): string
    {
        return $this->signKey;
    }
}