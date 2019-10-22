<?php

namespace App\Console\Commands;

use App\Modules\Reservation\Models\RsvtReservation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CancelExpiredReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rsvt:cancel_expired_reservations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancela reservaciones sin confirmar expiradas';

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
      $unconfirmedStatus = get_detail_parameters('estados-reserva', 'unconfirmed')[0]['id'];
      $inactiveStatus = get_detail_parameters('estados-reserva', 'inactive')[0]['id'];

      $reservations = RsvtReservation::with('responsible', 'location', 'reservable')->where('status_id', $unconfirmedStatus)->get();

      foreach ($reservations as $reservation) {
        $expireDate = Carbon::parse($reservation->expire_at);

        if (now() >= $expireDate) {
          $reservation->update([
            'observation' => 'Se cancela la reserva por no confirmación en el tiempo estipulado.',
            'status_id' => $inactiveStatus
          ]);


          $data = (object)[
            'subject' => 'Reserva cancelada',
            'email' => $reservation['email'],
            'name' => ucfirst(mb_strtolower($reservation->responsible->first_name)),
            'intro_lines' => [
              'Este correo es para informarle que su reserva de:',
              '<b>' . $reservation->reservable->reservable . ' en ' . $reservation->location->location . '</b>',
              '<b>ha sido cancelada.</b>',
              '<br><b>Observaciones:</b><br>',
              '' . $reservation['observation']
            ],
            'outro_lines' => [
              'Gracias por utilizar nuestros servicios.'
            ],
            'view' => 'emails.template',
            'text' => 'emails.template_plain',
          ];

          \Mail::to($data->email)->cc($reservation->location->email)->queue(new \App\Mail\Email($data));
        }
      }
    }
}
