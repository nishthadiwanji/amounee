<?php
namespace App\Repositories\Auth;

use Sentinel;
use Exception;
use Log;


class AuthRepository {

	public static function createUser($role, $attributes){
		$role = Sentinel::findRoleBySlug($role);
		if($role){
			$credentials = [
	            'first_name' =>  $attributes['first_name'],
	            'middle_name' =>  $attributes['middle_name'] ??  NULL,
	            'last_name' =>  $attributes['last_name'],
				'email'    =>  $attributes['email'],
				'country_code' => $attributes['country_code'],
	            'phone_number'=>  $attributes['phone_number'],
	            'password' =>  $attributes['password']
	        ];
			if(Sentinel::validForCreation($credentials)){
				try{
                	$user = Sentinel::registerAndActivate($credentials);
					$role->users()->attach($user);
	        		return $user;
				}
				catch(Exception $e){
                	Log::error(__('auth/backend/log.new_user_error'),[$e->getMessage()]);
				}
			}
			return false;
		}
		return false;
	}

    public static function updateUser($user,$attributes){
        $credential = [
            'first_name' =>  $attributes['first_name'],
            'middle_name' =>  $attributes['middle_name'] ?? NULL,
            'last_name' =>  $attributes['last_name'],
            'email'    =>  $attributes['email'],
			'country_code' => $attributes['country_code'],
            'phone_number'=>  $attributes['phone_number']
        ];
        if(Sentinel::validForUpdate($user, $credential)){
            return Sentinel::update($user, $credential);
        }
        return false;
    }

	public static function updateUserPassword($user,$attributes){
        $credential = [
            'password' =>  $attributes['password'],
            'password_confirmation' =>  $attributes['password_confirmation']
        ];
        if(Sentinel::validForUpdate($user, $credential)){
            return Sentinel::update($user, $credential);
        }
        return false;
    }

    public static function attachRole($role,$user){
        $role = Sentinel::findRoleBySlug($role);
        if($role){
            $role->users()->attach($user);
            return true;
		}
		return false;
    }

    public static function detachRole($role,$user){
        $role = Sentinel::findRoleBySlug($role);
        if($role){
            $role->users()->detach($user);
            return true;
		}
		return false;
    }
}
