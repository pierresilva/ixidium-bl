<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
  /**
   * The event listener mappings for the application.
   *
   * @var array
   */
  protected $listen = [
    'App\Events\Event' => [
      'App\Listeners\EventListener',
    ],
  ];

  /**
   * Register any events for your application.
   *
   * @return void
   */
  public function boot()
  {
    parent::boot();

    Event::listen('generic.event', function ($clientData) {
      return \Websockets::message('generic.event', [
        'message' => 'A message from a generic event fired in Laravel!',
        'data' => $clientData->data
      ]);
    });

    Event::listen('app.success', function ($clientData) {
      return \Websockets::success([
        'There was a Laravel App Success Event!'
      ]);
    });

    Event::listen('app.error', function ($clientData) {
      return \Websockets::error([
        'There was a Laravel App Error!'
      ]);
    });
  }
}
