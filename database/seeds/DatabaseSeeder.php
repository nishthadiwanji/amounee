<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        // $this->call(UsersTableSeeder::class);
        //common seeder which will run in production and local environment
        $this->call(RoleTableSeeder::class);
        $this->call(AdminSeeder::class);
        if (config('app.env') != 'production') {
            $this->call(TeamMemberSeeder::class);
            $this->call(CategorySeeder::class);
            $this->call(ArtisanSeeder::class);
            $this->call(ProductSeeder::class);
        }
    }
}