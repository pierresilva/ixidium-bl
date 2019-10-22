<?php

namespace App\Modules\Couchdb\Providers;

use pierresilva\Modules\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'couchdb');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'couchdb');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'couchdb');
        $this->loadConfigsFrom(__DIR__.'/../config');
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
