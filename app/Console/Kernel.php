<?php

namespace App\Console;

use App\Console\Commands\CancelExpiredReservations;
use Egulias\EmailValidator\Exception\CommaInDomain;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Psy\Command\Command;

class Kernel extends ConsoleKernel
{
  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
    //
    Commands\CancelExpiredReservations::class,
    Commands\EnvSetCommand::class,
    Commands\UpdateMigrationsTable::class,
    Commands\GenerateApiDoc::class,
    Commands\InverseSeed::class,
    Commands\SendComingQoutationsEmail::class,
    Commands\DatabaseDumpCommand::class,
    Commands\DatabaseDeleteBackupCommand::class,
  ];

  /**
   * Define the application's command schedule.
   *
   * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule)
  {
    $schedule->command('rsvt:cancel_expired_reservations')->hourly()->withoutOverlapping();
  }

  /**
   * Register the commands for the application.
   *
   * @return void
   */
  protected function commands()
  {
    $this->load(__DIR__.'/Commands');

    require base_path('routes/console.php');
  }


}
