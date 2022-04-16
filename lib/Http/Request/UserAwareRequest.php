<?php

namespace NAV\OnlineInvoice\Http\Request;

interface UserAwareRequest
{
    public function setUser(User $user): UserAwareRequest;
    
    public function getUser(): ?User;
}