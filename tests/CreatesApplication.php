<?php

namespace Tests;

use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
  private static $configurationApp = null;

  /**
   * Creates the application.
   *
   * @return \Illuminate\Foundation\Application
   */
  public function createApplication()
  {
    /*$app = require __DIR__.'/../bootstrap/app.php';

    $app->make(Kernel::class)->bootstrap();

    Hash::driver('bcrypt')->setRounds(4);

    return $app;*/

    return self::initialize();
  }

  public static function initialize()
  {

    if (is_null(self::$configurationApp)) {
      $app = require __DIR__ . '/../bootstrap/app.php';

      $app->loadEnvironmentFrom('.env.testing');

      $app->make(Kernel::class)->bootstrap();

      Hash::driver('bcrypt')->setRounds(4);

      \Artisan::call('migrate:refresh', ['--seed' => true]);

      self::$configurationApp = $app;

      return $app;
    }

    return self::$configurationApp;
  }

  public function tearDown()
  {
    if ($this->app) {
      foreach ($this->beforeApplicationDestroyedCallbacks as $callback) {
        call_user_func($callback);
      }

    }

    $this->setUpHasRun = false;

    if (property_exists($this, 'serverVariables')) {
      $this->serverVariables = [];
    }

    if (class_exists('Mockery')) {
      \Mockery::close();
    }

    $this->afterApplicationCreatedCallbacks = [];
    $this->beforeApplicationDestroyedCallbacks = [];
  }
}
