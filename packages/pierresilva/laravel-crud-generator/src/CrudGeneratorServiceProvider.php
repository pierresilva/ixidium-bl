<?php

namespace pierresilva\CrudGenerator;

use Illuminate\Support\ServiceProvider;

class CrudGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/crudgenerator.php' => config_path('crudgenerator.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/views/' => base_path('resources/views/'),
        ]);

        if (\App::VERSION() <= '5.2') {
            $this->publishes([
                __DIR__ . '/../publish/css/app.css' => public_path('css/app.css'),
            ]);
        }

        $this->publishes([
            __DIR__ . '/stubs/' => base_path('resources/crud-generator/'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(
            'pierresilva\CrudGenerator\Commands\CrudAngularFrontEndCommand',
            'pierresilva\CrudGenerator\Commands\CrudAngularCommand',
            'pierresilva\CrudGenerator\Commands\CrudApiTestCommand',
            'pierresilva\CrudGenerator\Commands\CrudCommand',
            'pierresilva\CrudGenerator\Commands\CrudControllerCommand',
            'pierresilva\CrudGenerator\Commands\CrudModelCommand',
            'pierresilva\CrudGenerator\Commands\CrudMigrationCommand',
            'pierresilva\CrudGenerator\Commands\CrudViewCommand',
            'pierresilva\CrudGenerator\Commands\CrudLangCommand',
            'pierresilva\CrudGenerator\Commands\CrudApiCommand',
            'pierresilva\CrudGenerator\Commands\CrudApiControllerCommand',
            'pierresilva\CrudGenerator\Commands\CrudRequestCommand',
            'pierresilva\CrudGenerator\Commands\CrudMigrationPivotCommand',
            'pierresilva\CrudGenerator\Commands\CrudFactoryCommand',
            'pierresilva\CrudGenerator\Commands\CrudSeederCommand'
        );
    }
}
