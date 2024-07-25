<?php
namespace App\Repositories\Auth;

use Carbon\Carbon;
use Cartalyst\Sentinel\Users\UserInterface;

class BanUserRepository {
    public function isBanned(UserInterface $user){
        return $user->isBanned();
    }
    public function ban(UserInterface $user){
        if(!$this->isBanned($user)){
            $user->banned = true;
            $user->banned_at = Carbon::now();
            $user->save();
            return true;
        }
        return false;
    }
    public function unban(UserInterface $user){
        if($this->isBanned($user)){
            $user->banned = false;
            $user->banned_at = null;
            $user->save();
            return true;
        }
        return false;
    }
}
