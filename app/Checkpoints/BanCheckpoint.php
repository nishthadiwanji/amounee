<?php 

namespace App\Checkpoints;

use Cartalyst\Sentinel\Checkpoints\CheckpointInterface;
use Cartalyst\Sentinel\Checkpoints\AuthenticatedCheckpoint;
use App\Repositories\Auth\BanUserRepository;
use Cartalyst\Sentinel\Users\UserInterface;
use App\Exceptions\Auth\BannedException;

class BanCheckpoint implements CheckpointInterface{

    use AuthenticatedCheckpoint;

    protected $user;

    public function __construct(BanUserRepository $user){
        $this->user = $user;
    }

    public function login(UserInterface $user){
        return $this->checkBan($user);
    }

    public function check(UserInterface $user){
        return $this->checkBan($user);
    }

    protected function checkBan(UserInterface $user){
        $isBanned = $this->user->isBanned($user);
        if ($isBanned) {
            $exception = new BannedException("You're access to the portal has been banned.");
            $exception->setUser($user);
            throw $exception;
        }
    }
}
