<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect(config('constant.roles'))->each(function ($value,$key){
            $role = Sentinel::getRoleRepository()->createModel()->create([
                'name' => $value,
                'slug' => $key,
            ]);
        });
    }
}
