<?php

namespace pierresilva\NgGenerators;

use Illuminate\Support\ServiceProvider;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/ng-generators.php' => config_path('ng-generators.php'),
        ]);

        $this->publishes([
            __DIR__ . '/Console/Commands/Stubs' => base_path('resources/ng-generator/stubs'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            'pierresilva\NgGenerators\Console\Commands\AngularModule',
            'pierresilva\NgGenerators\Console\Commands\AngularComponent',
            'pierresilva\NgGenerators\Console\Commands\PwaManifest',
        ]);

        $this->mergeConfigFrom(__DIR__.'/../config/ng-generators.php', 'ng-generators');
    }
}
