<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DemoEmail extends Mailable
{
  use Queueable, SerializesModels;

  public $demo;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($demo)
  {
    $this->demo = $demo;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this->from(env('BASELINE_MAIL_USERNAME'))
      ->to($this->demo->email)
      ->view('emails.demo.demo')
      ->text('emails.demo.demo_plain')
      ->with(
        [
          'testVarOne' => '1',
          'testVarTwo' => '2',
        ]
      )
      ->attach(public_path('/images') . '/default.jpg', [
        'as' => 'demo.jpg',
        'mime' => 'image/jpeg',
      ]);
  }
}
