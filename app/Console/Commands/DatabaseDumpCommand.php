<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DatabaseDumpCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'db:dump';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    //
    try {
      \pierresilva\DbDumper\Databases\MySql::create()
        ->setHost(env('BASELINE_DB_HOST'))
        ->setPort(env('BASELINE_DB_PORT'))
        ->setDbName(env('BASELINE_DB_DATABASE'))
        ->setUserName(env('BASELINE_DB_USERNAME'))
        ->setPassword(env('BASELINE_DB_PASSWORD'))
        ->addExtraOption('--single-transaction')
        ->dumpToFile(public_path('db-dump.sql'));

      return $this->info('Respaldo de la base de datos generado en: ' . env('BASELINE_APP_URL') . '/db-dump.sql');
    } catch (\Exception $exception) {
      return $this->error($exception->getMessage());
    }

  }

}
