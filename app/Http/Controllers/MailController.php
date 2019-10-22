<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\DemoEmail;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    //

  public function sendDemoEmail()
  {
    $demo = new \stdClass();
    $demo->demo_one = 'Demo One Value';
    $demo->demo_two = 'Demo Two Value';
    $demo->sender = 'Sender User Name';
    $demo->receiver = 'Receiver User Name';
    $demo->email = 'pierremichelsilva@gmail.com';
    $data = Mail::to($demo->email)->queue(new DemoEmail($demo));

    return response()->json([
      'message' => 'Email enviado con éxito!',
      'data' => $data
    ]);

  }
}
