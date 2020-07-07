<?php

namespace NAV\OnlineInvoice\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

class Software
{
    private static $validSoftwareKeys = [
        'softwareId',
        'softwareName',
        'softwareOperation',
        'softwareMainVersion',
        'softwareDevName',
        'softwareDevContact',
        'softwareDevCountryCode',
        'softwareDevTaxNumber',
    ];

    public static function initWithArray($array)
    {
        $software = new self();
        
        foreach (self::$validSoftwareKeys as $keyName) {
            if (!isset($array[$keyName])) {
                continue;
            }
            
            $value = $array[$keyName];
            $setter = 'set'. str_replace('software', '', $keyName);
            
            $software->$setter($value);
        }
        
        return $software;
    }
    
    /**
     * TODO: documentation
     *
     * @Assert\NotBlank(groups={"v1.0", "v1.1", "v2.0"})
     * @Assert\Regex("/^[0-9A-Z\-]{18}$/", groups={"v1.0", "v1.1", "v2.0"})
     */
    protected $id;
    
    /**
     * setter for id
     *
     * @param mixed 
     * @return self
     */
    public function setId(string $value)
    {
        $this->id = $value;
        return $this;
    }
    
    /**
     * getter for id
     * 
     * @return mixed return value for 
     */
    public function getId(): string
    {
        return $this->id;
    }
    
    /**
     * TODO: documentation
     *
     * @Assert\NotBlank(groups={"v2.0"})
     * @Assert\Length(max=50, groups={"v1.0", "v1.1", "v2.0"})
     * @Assert\Regex("/^.*[^\s].*$/", groups={"v1.0", "v1.1", "v2.0"})
     */
    protected $name;
    
    /**
     * setter for name
     *
     * @param mixed 
     * @return self
     */
    public function setName($value)
    {
        $this->name = $value;
        return $this;
    }
    
    /**
     * getter for name
     * 
     * @return mixed return value for 
     */
    public function getName()
    {
        return $this->name;
    }
    
    const OPERATIONS = ['LOCAL_SOFTWARE', 'ONLINE_SERVICE'];
    const OPERATION_LOCAL_SOFTWARE = 'LOCAL_SOFTWARE';
    const OPERATION_ONLINE_SERVICE = 'ONLINE_SERVICE';

    /**
     * TODO: documentation
     *
     * @Assert\NotBlank(groups={"v2.0"})
     * @Assert\Choice(choices=Software::OPERATIONS, groups={"v1.0", "v1.1", "v2.0"})
     */
    protected $operation;
    
    /**
     * setter for operation
     *
     * @param mixed 
     * @return self
     */
    public function setOperation(?string $value)
    {
        $this->operation = $value;
        return $this;
    }
    
    /**
     * getter for operation
     * 
     * @return mixed return value for 
     */
    public function getOperation(): ?string
    {
        return $this->operation;
    }
    
    /**
     * TODO: documentation
     *
     * @Assert\NotBlank(groups={"v2.0"})
     * @Assert\Length(max=15, groups={"v1.0", "v1.1", "v2.0"})
     * @Assert\Regex("/^.*[^\s].*$/", groups={"v1.0", "v1.1", "v2.0"})
     */
    protected $mainVersion;
    
    /**
     * setter for mainVersion
     *
     * @param mixed 
     * @return self
     */
    public function setMainVersion($value)
    {
        $this->mainVersion = $value;
        return $this;
    }
    
    /**
     * getter for mainVersion
     * 
     * @return mixed return value for 
     */
    public function getMainVersion()
    {
        return $this->mainVersion;
    }
    
    /**
     * TODO: documentation
     *
     * @Assert\NotBlank(groups={"v2.0"})
     * @Assert\Length(max=512, groups={"v1.0", "v1.1", "v2.0"})
     * @Assert\Regex("/^.*[^\s].*$/", groups={"v1.0", "v1.1", "v2.0"})
     */
    protected $devName;
    
    /**
     * setter for devName
     *
     * @param mixed 
     * @return self
     */
    public function setDevName($value)
    {
        $this->devName = $value;
        return $this;
    }
    
    /**
     * getter for devName
     * 
     * @return mixed return value for 
     */
    public function getDevName()
    {
        return $this->devName;
    }
    
    /**
     * TODO: documentation
     *
     * @Assert\Length(max=200, groups={"v1.0", "v1.1", "v2.0"})
     * @Assert\Regex("/^.*[^\s].*$/", groups={"v1.0", "v1.1", "v2.0"})
     */
    protected $devContact;
    
    /**
     * setter for devContact
     *
     * @param mixed 
     * @return self
     */
    public function setDevContact($value)
    {
        $this->devContact = $value;
        return $this;
    }
    
    /**
     * getter for devContact
     * 
     * @return mixed return value for 
     */
    public function getDevContact()
    {
        return $this->devContact;
    }
    
    /**
     * TODO: documentation
     *
     * @Assert\Length(max=2, groups={"v1.0", "v1.1", "v2.0"})
     * @Assert\Country(groups={"v1.0", "v1.1", "v2.0"})
     */
    protected $devCountryCode;
    
    /**
     * setter for devCountryCode
     *
     * @param mixed 
     * @return self
     */
    public function setDevCountryCode($value)
    {
        $this->devCountryCode = $value;
        return $this;
    }
    
    /**
     * getter for devCountryCode
     * 
     * @return mixed return value for 
     */
    public function getDevCountryCode()
    {
        return $this->devCountryCode;
    }
    
    /**
     * @var string
     */
    protected $devTaxNumber;
    
    /**
     * setter for devTaxNumber
     *
     * @param mixed 
     * @return self
     */
    public function setDevTaxNumber($value)
    {
        $this->devTaxNumber = $value;
        return $this;
    }
    
    /**
     * getter for devTaxNumber
     * 
     * @return mixed return value for 
     */
    public function getDevTaxNumber()
    {
        return $this->devTaxNumber;
    }
}
