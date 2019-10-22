<?php

use Illuminate\Database\Seeder;

class SigasBSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    //

    factory(\App\Sigas::class, 10000)->create();

  }
}
