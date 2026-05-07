<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TripConfirmationClient extends Mailable
{
    use Queueable, SerializesModels;

    public $trip;
    public $user;
    public $vehicleType;

    public function __construct($trip, $user, $vehicleType)
    {
        $this->trip = $trip;
        $this->user = $user;
        $this->vehicleType = $vehicleType;
    }

    public function build()
    {
        return $this->subject('ATLAS AND CO - Confirmation de votre course')
                    ->view('emails.trip-confirmation-client');
    }
}
