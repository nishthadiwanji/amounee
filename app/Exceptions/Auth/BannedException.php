<?php
namespace App\Exceptions\Auth;

use Cartalyst\Sentinel\Users\UserInterface;
use RuntimeException;

class BannedException extends RuntimeException {
    
    protected $user;

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }
}
