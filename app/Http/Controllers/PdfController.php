<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PdfController extends Controller
{
  //

  public function __construct()
  {
    setlocale(LC_ALL, "es_CO@peso", "es_CO", "esp");
  }

  public function makePdf()
  {

    $data = [
      'name' => 'Este es mi nombre!',
      'data' => [ 1, 2, 3, 4, 5],
    ];

    $view = \View::make('pdf.test.test', $data);
    $html = (string)$view;

    $pdf = \PDF::loadView('pdf.comfamiliar-letter', ['html' => $html]);

    return $pdf->download('comfamiliar-test.pdf');

  }
}
