<?php
// app/Mail/RentalNotificationAdmin.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RentalNotificationAdmin extends Mailable
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
        return $this->subject('🔔 Nouvelle demande de location - ATLAS AND CO')
                    ->view('emails.rental-notification-admin');
    }
}
