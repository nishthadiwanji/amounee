<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\TeamMember\TeamMember;
use App\Observers\TeamMember\TeamMemberObserver;
use App\Models\Artisan\Artisan;
use App\Observers\Artisan\ArtisanObserver;
use App\Models\Product\Product;
use App\Observers\Product\ProductObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        // Observers related to all users of the system are mentioned below :  
        TeamMember::observe(TeamMemberObserver::class);
        Artisan::observe(ArtisanObserver::class);
        Product::observe(ProductObserver::class);

        // Observers related to product are mentioned below :

        // Observers related to orders are mentioned below : 

    }
}
