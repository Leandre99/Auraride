<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TripNotificationAdmin extends Mailable
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
        return $this->subject('🔔 Nouvelle course réservée - ATLAS AND CO')
                    ->view('emails.trip-notification-admin');
    }
}
