<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['layouts.header', 'layouts.user-panel'],'App\Composers\HeaderComposer');
        View::composer(['widgets.all-team-members'], 'App\Composers\Widgets\AllTeamMembersCount');
        View::composer(['widgets.artisan-pending'], 'App\Composers\Widgets\ArtisanPendingCount');
        View::composer(['widgets.artisan-approved'], 'App\Composers\Widgets\ArtisanApproveCount');
        View::composer(['widgets.artisan-rejected'], 'App\Composers\Widgets\ArtisanRejectCount');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
