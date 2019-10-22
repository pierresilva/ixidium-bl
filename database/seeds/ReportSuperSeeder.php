<?php

use App\Modules\Reports\Database\Seeds\ServicesProgramsSeeder;
use App\Modules\Reports\Database\Seeds\CodesReferenceSeeder;
use App\Modules\Reports\Database\Seeds\HomologationCodeSeeder;
use Illuminate\Database\Seeder;


class ReportSuperSeeder extends Seeder
{

    public function run ()
    {
        $this->call(ServicesProgramsSeeder::class);
        $this->call(CodesReferenceSeeder::class);
        $this->call(HomologationCodeSeeder::class);
    }
}