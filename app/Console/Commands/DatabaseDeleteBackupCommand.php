<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DatabaseDeleteBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:dump:delete';

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
      if (\File::exists(public_path('db-dump.sql'))) {
        \File::delete(public_path('db-dump.sql'));
        return $this->info('Respaldo de base de datos eliminado!');
      }

      return $this->info('No se encontro respaldo de base de datos!');
    }
}
