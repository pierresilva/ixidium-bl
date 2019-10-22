<?php
namespace pierresilva\Websockets;

use Illuminate\Support\ServiceProvider;

class WebsocketsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

		$this->app->singleton('websockets', function($app){
			return new WebsocketsAppResponse();
    });

		$this->app->singleton('command.websockets.start', function($app)
		{
			return new Websockets();
		});
		$this->commands('command.websockets.start');
	}


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('websockets','command.websockets.start');
	}

}
