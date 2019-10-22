<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
      setlocale(LC_ALL,"es_ES");
      \Carbon\Carbon::setLocale(config('app.locale'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            $this->app->register(\pierresilva\CrudGenerator\CrudGeneratorServiceProvider::class);
            $this->app->register(\pierresilva\NgGenerators\LaravelServiceProvider::class);
            $this->app->register(\Mpociot\ApiDoc\ApiDocGeneratorServiceProvider::class);
        }
    }
}
