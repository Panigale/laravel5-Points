<?php

namespace Panigale;

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
        $this->addMigration();
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
    private function addMigration()
    {
        $this->publishes([
            __DIR__.'/database/migrations' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * add config
     */
    private function addConfig()
    {
        $this->publishes([
            __DIR__.'/config/points.php' => config_path('points.php')
        ] ,'config');
    }
}
