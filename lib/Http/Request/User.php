<?php

namespace NAV\OnlineInvoice\Http\Request;

class User
{
    protected $login;
    
    /**
     * setter for login
     *
     * @param mixed 
     * @return self
     */
    public function setLogin($value)
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
    public function setPassword($value)
    {
        $this->password = $value;
        return $this;
    }
    
    /**
     * getter for password
     * 
     * @return mixed return value for 
     */
    public function getPassword()
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
    public function setTaxNumber($value)
    {
        $this->taxNumber = substr($value, 0, 8);
        return $this;
    }
    
    /**
     * getter for taxNumber
     * 
     * @return mixed return value for 
     */
    public function getTaxNumber()
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
    public function setSignKey($value)
    {
        $this->signKey = $value;
        return $this;
    }
    
    /**
     * getter for signKey
     * 
     * @return mixed return value for 
     */
    public function getSignKey()
    {
        return $this->signKey;
    }
}