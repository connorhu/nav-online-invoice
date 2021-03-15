<?php

namespace NAV\OnlineInvoice\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

trait UserAwareTrait
{
    /**
     * @Assert\NotBlank(groups={"v1.0", "v1.1", "v2.0", "v3.0"})
     */
    protected $user;
    
    public function setUser(User $value): UserAwareRequest
    {
        if ($this->user !== $value) {
            $this->user = $value;
            $value->setRequest($this);
        }
        
        return $this;
    }
    
    public function getUser(): User
    {
        return $this->user;
    }
    
}