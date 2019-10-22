<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateMigrationsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:migrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar tabla migraciones para primer despliegue de Octubre.';

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
        $migration = \DB::table('migrations')
          ->where('id', 2230)
          ->update([
            'migration' => '2019_08_02_152643_create_lib_library_massive_activities_table'
          ]);

        if ($migration) {
          return $this->info('Migracion actualizada!');
        }

        return $this->info('No se encontró la migración!');
    }
}
