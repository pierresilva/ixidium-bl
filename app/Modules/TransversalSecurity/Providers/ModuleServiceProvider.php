<?php

namespace App\Modules\TransversalSecurity\Providers;

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
        $this->loadTranslationsFrom(__DIR__.'/../Resources/Lang', 'transversal-security');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'transversal-security');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'transversal-security');
        $this->loadConfigsFrom(__DIR__.'/../config');        
        $this->mergeConfigFrom(__DIR__."/../config/connection.php", 'transversal-security');
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
