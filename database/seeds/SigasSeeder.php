<?php

use Illuminate\Database\Seeder;

class SigasSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    //
    \App\Sigas::truncate();

    for ($i = 0; $i < 100; $i++) {
      factory(\App\Sigas::class)->create([
        'contributor_id' => null,
      ]);
    }

    for ($i = 0; $i < 1; $i++) {
      factory(\App\Sigas::class, 1000)->create();
    }

    for ($i = 0; $i < 1; $i++) {
      factory(\App\Sigas::class, 1000)->create();
    }

  }
}
