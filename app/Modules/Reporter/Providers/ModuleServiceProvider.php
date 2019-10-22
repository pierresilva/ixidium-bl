<?php

namespace App\Modules\Reporter\Providers;

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
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'reporter');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'reporter');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'reporter');
        $this->loadConfigsFrom(__DIR__.'/../config');
        // $this->mergeConfigFrom(__DIR__."/../config/database.php", 'database');
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
