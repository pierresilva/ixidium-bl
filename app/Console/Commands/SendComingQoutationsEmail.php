<?php

namespace App\Console\Commands;

use App\Modules\Quotations\Http\Controllers\Api\QuotationController;
use App\Modules\Quotations\Models\QuotConsultant;
use Illuminate\Console\Command;

class SendComingQoutationsEmail extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'email:coming-quotations';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Send email to consultants and auxiliaries notification for coming unconfirmed quotations.';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    //

    $comingQuotations = QuotationController::getUnconfirmedQuotations();

    $consultants = QuotConsultant::with('thirdParty.emailPrincipal')->where('type_consultant_id', 138)->get()->toArray();

    $consultantsCnt = 0;

    foreach ($consultants as $consultant) {

      $consultants[$consultantsCnt]['quotations']['fifteen'] = [];
      $consultants[$consultantsCnt]['quotations']['three'] = [];

      foreach ($comingQuotations['less_fifteen_days'] as $comingQuotation) {

        if ($comingQuotation['assessor']['id'] == $consultant['third_party_id']) {
          $consultants[$consultantsCnt]['quotations']['fifteen'][] = $comingQuotation;
        }

      }

      foreach ($comingQuotations['less_three_days'] as $comingQuotation) {

        if ($comingQuotation['assessor']['id'] == $consultant['third_party_id']) {
          $consultants[$consultantsCnt]['quotations']['three'][] = $comingQuotation;
        }

      }

      $consultantsCnt++;
    }

    foreach ($consultants as $consultant) {
      if (count($consultant['quotations']['fifteen']) > 0 || count($consultant['quotations']['three']) > 0) {
        $this->buildEmail($consultant);
      }
    }

  }


  private function buildEmail($consultant)
  {
    $data = (object) [
      'subject' => 'Cotizaciones sin confirmar',
      'email' => $consultant['third_party']['email_principal']['email'],
      'name' => $consultant['third_party']['name'],
      'intro_lines' => [],
      'anchor' => [
        'url' => config('app.url') . '/#/quotations-admin/new-quotation',
        'text' => 'Verificar Cotizaciones',
      ],
      'view' => 'emails.template',
      'text' => 'emails.template_plain',
    ];

    if (count($consultant['quotations']['fifteen']) > 0) {
      $data->intro_lines[0] = 'Tiene ' . count($consultant['quotations']['fifteen']) . ' cotizaciones por confirmar menores a 15 dias para el evento.';
    }

    if (count($consultant['quotations']['three']) > 0) {
      $data->intro_lines[1] = 'Tiene ' . count($consultant['quotations']['three']) . ' cotizaciones por confirmar menores a 3 dias para el evento.';
    }

    $this->sendEmail($consultant['third_party']['email_principal']['email'], $data);

  }


  private function sendEmail($email, $data)
  {

    try {
      \Mail::to($email)->queue(new \App\Mail\Email($data));
    } catch (\Exception $exception) {
      // dd($exception);

      return $exception;
    }

    $this->info('Se envio el email con éxito!');

  }
}
