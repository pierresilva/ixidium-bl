<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Hollydays;
use App\Helpers\BusinessDays;
use Carbon\Carbon;

class BusinessDaysController extends Controller
{
    //
    public function index()
    {
      // año del que se desea obtener los festivos
      $year = '2018';
      // instanciar la clase Holydays
      $hds = new Hollydays();
      // Instanciar la clase BusinessDays
      $bds = new BusinessDays();
      // obtener los dias festivos
      $holydays = $hds->festivos($year);
      // formatear los dias vestivos al tipo Carbon
      foreach ($holydays[$year] as $key =>$value) {
        foreach ($value as $k => $v) {
          // agregar los festivos a la instancia de businessdays
          $bds->addHoliday(Carbon::createFromDate($year, $key, $k));
        }
      }
      // agregar los dias no laborales la instancia de businessdays
      $weekEndDays = [0, 6];
      $bds->setWeekendDays($weekEndDays);
      // obtener cantidad de dias laborales entre dos fechas
      $daysBetween = $bds->daysBetween(
        Carbon::createFromDate($year, '11', '01'),
        Carbon::createFromDate($year, '11', '06')->addDay()
      );

      // dd($daysBetween);
    }
}
