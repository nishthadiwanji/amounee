<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        foreach (config('users.'.config('app.env').'.admin') as $user){
            $new_user = $this->createUser($user);
            $this->attachRole($new_user,'admin');
            $this->attachRole($new_user,'team_member');
        }
    }
    
    protected function createUser($var){
        $user = Sentinel::registerAndActivate($var);
        return $user;
    }
    protected function attachRole($user,$role){
        Sentinel::findRoleBySlug($role)->users()->attach($user);
        return true;
    }
}
