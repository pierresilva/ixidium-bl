<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SigasProcessAffiliates extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'sigas:process-affiliates
                            {start-date? : Date to start search for recently updated affiliates.}
                            {end-date? : Date to obtain affiliates updated until this date.}';

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
  }

  private function getItemByName($name)
  {
    $array = [
      "tipodocumento" => "document_type",
      "identificacion" => "identification",
      "trabajador" => "affiliate",
      "fechanacimiento" => "birthdate",
      "edad" => "age",
      "sexo" => "gender",
      "categoria_persona" => "category",
      "EsBeneficiario" => "150056-",
      "estado" => "I",
      "fechaingreso" => "2018-08-21",
      "fecharetiro" => "2019-01-31",
      "fechafidelidad" => "1",
      "departamento" => "Huila",
      "municipio" => "NEIVA",
      "direccion" => "CALLE 8 22- 31",
      "telefono" => "3203598707",
      "fechaactualizacion" => "2012-08-31"
    ];
  }
}
