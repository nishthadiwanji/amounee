<?php

use Illuminate\Database\Seeder;
use App\Models\Artisan\Artisan;
use App\Repositories\Artisan\ArtisanRepository;
use Illuminate\Support\Str;
use App\Models\Auth\SentinelUser;

class ArtisanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $artisan_repo;
    public function __construct(ArtisanRepository $artisan_repo) {
        $this->artisan_repo = $artisan_repo;
    }
    public function run()
    {
        try{
            // $admins = Sentinel::findRoleBySlug('admin')->users()->get();
            // foreach($admins as $admin) {
            //     $artisan = [
            //         'user_id' => $admin->id,
            //     ];
            //     $this->enterDataInArtisan($artisan);
            // }
            if(config('app.env') == 'local'){
                foreach(config('users.local.artisan') as $user){
                    $attributes = factory(SentinelUser::class)->make($user);
                    $this->prepareAndCreateArtisan($attributes);  
                }
            }
        }
        catch(\Exception $e){
            \Log::error("error in artisans seeder => ",[$e->getMessage(), $e->getTraceAsString()]);
        }
    }
    protected function prepareAndCreateArtisan($attributes){
        return $this->artisan_repo->createArtisan($attributes, 1, 0);
    }
    // protected function enterDataInArtisan($data){
    //     $data['added_by'] = 1;
    //     return Artisan::create($data);
    // }
}
