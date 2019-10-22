<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\Hollydays;

class HollydaysController extends Controller
{
    //

    private $hollydays;

    public function __construct()
    {
      $this->hollydays = new Hollydays();
    }


    public function getHollydays($year)
    {
      $data = $this->hollydays->festivos($year);

      return response()->json([
        'message' => 'Se obtuvieron los dias feriados!',
        'data' => $data
      ]);

    }

    public function getHollyday($d, $m)
    {
      $data = $this->hollydays->esFestivo($d, $m);

      return response()->json([
        'message' => 'Se obtuvieron los dias feriados!',
        'data' => $data
      ]);
    }
}
