<?php

namespace App\Models\Auth;

use Cartalyst\Sentinel\Users\EloquentUser as SUser;
use Spatie\Activitylog\Traits\CausesActivity;
use App\Traits\BanUser;
use App\Traits\Encrypted;
use Sentinel;
use Session;
use Tymon\JWTAuth\Contracts\JWTSubject;

class SentinelUser extends SUser implements JWTSubject
{
    use CausesActivity;
    use BanUser, Encrypted;

    protected $fillable = [
        'email',
        'password',
        'permissions',
        'first_name',
        'last_name',
        'country_code',
        'phone_number',
        'middle_name',
        'otp'
    ];

	protected $loginNames = ['email'];
    
    // Please ADD this two methods at the end of the class
      public function getJWTIdentifier()
      {
        return $this->getKey();
      }
      public function getJWTCustomClaims()
      {
        return [];
      }

    public function full_phone_number(){
        return trim(trim($this->country_code)." ".$this->phone_number);
    }

    public function teamMember(){
         return $this->hasOne('App\Models\TeamMember\TeamMember','user_id','id');
    }

    public function artisan(){
        return $this->hasOne('App\Models\Artisan\Artisan','user_id','id');
   }

    public function individual(){
        return $this->hasOne('App\Models\Individual\Individual','user_id', 'id');
    }

    public function detachAssignedRoles(){
        $this->roles()->detach($this->getAssignedRoles());
    }

	public function getAssignedRoles(){
		return $this->roles()->pluck('id')->toArray();
	}

	public function getAssignedRolesName(){
        return implode(",",$this->roles()->pluck('name')->toArray());
    }

    public function name(){
        return $this->first_name." ".$this->last_name;
    }

    public function full_name(){
        return trim(trim($this->first_name." ".$this->middle_name)." ".$this->last_name);
    }
    
    public function isBanned(){
        return (bool) $this->banned;
    }

    public function isAdmin(){
        if(Sentinel::inRole('admin')){
            return true;
        }
        return false;
    }

    public function isTeamMember(){
        if(Sentinel::inRole('team_member')){
            return true;
        }
        return false;
    }
    
    public function setupSystemInfoAfterLogin(){
        if($this->id == Sentinel::getUser()->id){
            session()->put('full_name',$this->first_name." ".$this->last_name);
            return true;
        }
        return false;
    }

    public function logoutFromSystem(){
        if($this->id == Sentinel::getUser()->id){
            Sentinel::logout();
            Session::forget('full_name');
            request()->session()->flush();
            return true;
        }
        return false;
    }
}
