<?php

namespace Panigale\Point;

use Illuminate\Support\ServiceProvider;

class PointSystemServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->addConfig();
        $this->loadMigration();
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

    /**
     * add migration
     */
    private function loadMigration()
    {
        $this->loadMigrationsFrom(__DIR__.'../database/migrations');
    }

    /**
     * add config
     */
    private function addConfig()
    {
        $this->publishes([
            __DIR__.'../config/points.php' => config_path('points.php')
        ] ,'config');
    }
}
