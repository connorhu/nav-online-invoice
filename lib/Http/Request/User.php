<?php

namespace NAV\OnlineInvoice\Http\Request;

class User
{
    protected $request;
    
    /**
     * setter for request
     *
     * @param mixed 
     * @return self
     */
    public function setRequest(UserAwareRequest $value): self
    {
        if ($this->request !== $value) {
            $this->request = $value;
        }
        
        return $this;
    }
    
    /**
     * getter for request
     * 
     * @return mixed return value for 
     */
    public function getRequest(): UserAwareRequest
    {
        return $this->request;
    }
    
    protected $login;
    
    /**
     * setter for login
     *
     * @param mixed 
     * @return self
     */
    public function setLogin(string $value): self
    {
        $this->login = $value;
        return $this;
    }
    
    /**
     * getter for login
     * 
     * @return mixed return value for 
     */
    public function getLogin()
    {
        return $this->login;
    }
    
    protected $password;
    
    /**
     * setter for password
     *
     * @param mixed 
     * @return self
     */
    public function setPassword(string $value): self
    {
        $this->password = $value;
        return $this;
    }
    
    /**
     * getter for password
     * 
     * @return mixed return value for 
     */
    public function getPassword(): string
    {
        return $this->password;
    }
    
    protected $taxNumber;
    
    /**
     * setter for taxNumber
     *
     * @param mixed 
     * @return self
     */
    public function setTaxNumber(string $value): self
    {
        $this->taxNumber = substr($value, 0, 8);
        return $this;
    }
    
    /**
     * getter for taxNumber
     * 
     * @return mixed return value for 
     */
    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }
    
    protected $signKey;
    
    /**
     * setter for signKey
     *
     * @param mixed 
     * @return self
     */
    public function setSignKey(string $value): self
    {
        $this->signKey = $value;
        return $this;
    }
    
    /**
     * getter for signKey
     * 
     * @return mixed return value for 
     */
    public function getSignKey(): string
    {
        return $this->signKey;
    }
}