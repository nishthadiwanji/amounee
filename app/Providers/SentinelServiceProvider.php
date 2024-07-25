<?php

namespace App\Providers;

use App\Checkpoints\BanCheckpoint;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Arr;
use App\Repositories\Auth\BanUserRepository;
class SentinelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBanCheckpoint();
    }

    protected function registerBanCheckpoint()
    {
        $this->registerBans();

        $this->app->singleton('sentinel.checkpoint.ban', function ($app) {
            return new BanCheckpoint($app['sentinel.bans']);
        });
    }

    protected function registerBans()
    {
        $this->app->singleton('sentinel.bans', function ($app) {
            $config = $app['config']->get('cartalyst.sentinel');

            $model   = Arr::get($config, 'users.model');

            return new BanUserRepository($model);
        });
    }
}
