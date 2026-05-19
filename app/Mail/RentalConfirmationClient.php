<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RentalConfirmationClient extends Mailable
{
    use Queueable, SerializesModels;

    public $rental;
    public $user;
    public $vehicleType;

    public function __construct($rental, $user, $vehicleType)
    {
        $this->rental = $rental;
        $this->user = $user;
        $this->vehicleType = $vehicleType;
    }

    public function build()
    {
        return $this->subject('ATLAS TAXI / VTC - Confirmation de votre demande de location')
                    ->view('emails.rental-confirmation-client');
    }
}
