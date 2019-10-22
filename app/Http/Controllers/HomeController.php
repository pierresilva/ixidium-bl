<?php

namespace App\Http\Controllers;

use App\Mail\OrderShipped;
use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function sendEmail()
    {
        //\Mail::to('jusevico@gmail.com')->queue(new OrderShipped());
        //echo ":)";

        // Enter the share name for your USB printer here
        //$connector = "POS-58";
        //$connector = new WindowsPrintConnector("POS-58");
        $connector = new WindowsPrintConnector("TMT88V");
        /* Print a "Hello world" receipt" */
        $printer = new Printer($connector);
        $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);

        $printer->text("Porque es tan manquito inge , con Mundo");

        /*
            Hacemos que el papel salga. Es como
            dejar muchos saltos de línea sin escribir nada
        */
        $printer->feed();

        /*
            Cortamos el papel. Si nuestra impresora
            no tiene soporte para ello, no generará
            ningún error
        */
        $printer->cut();

        /*
            Por medio de la impresora mandamos un pulso.
            Esto es útil cuando la tenemos conectada
            por ejemplo a un cajón
        */
        $printer->pulse();

        /*
            Para imprimir realmente, tenemos que "cerrar"
            la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
        */
        $printer->close();
        // echo "Sudah di Print";
    }

    public function calculateTax()
    {
        $data = get_tax_product_by_id(10);
        // dd($data);
    }
}
