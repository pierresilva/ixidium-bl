<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InverseSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:seed:inverse
                            {prefix? : Prefix for generated classes.}';

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
      $tableNames = [];
      if (env('BASELINE_DB_CONNECTION') == 'sqlsrv') {
        $tables = \DB::select("SELECT * FROM sys.Tables;");
        foreach ($tables as $table) {
          $tableNames[] = $table->name;
        }
      }

      if (env('BASELINE_DB_CONNECTION') == 'mysql') {
        $tables = \DB::select("SHOW TABLES");
        foreach ($tables as $table) {
          $tableNames[] = $table->name;
        }
      }

      $tableNamesText = implode(',', $tableNames);
      // php artisan iseed my_table --classnameprefix=Customized

      $exitCode = \Artisan::call('iseed', [
        'tables' => $tableNamesText,
        '--classnameprefix' => $this->argument('version') ? $this->argument('version') : 'Inverse',
        '--force' => true
      ]);

      return $this->info('Inverse seeder done!');

    }
}
