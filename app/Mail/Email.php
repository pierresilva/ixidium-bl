<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Email extends Mailable
{
  use Queueable, SerializesModels;

  public $params;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($params)
  {
    $this->params = $params;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    $this->from(env('BASELINE_MAIL_USERNAME', 'recreaweb@comfamiliarhuila.com'));
    $this->to($this->params->email);
    if (isset($this->params->replyTo) && $this->params->replyTo !== null) {
      $this->replyTo($this->params->replyTo);
    }
    $this->subject($this->params->subject);
    $this->view($this->params->view);
    $this->text($this->params->text);

    if (isset($this->params->attach)) {
      foreach ($this->params->attach as $value) {
        $this->attach($value['route'], [
          'as' => $value['as'],
          'mime' => $value['mime'],
        ]);
      }
    }

    return $this;
  }
}
