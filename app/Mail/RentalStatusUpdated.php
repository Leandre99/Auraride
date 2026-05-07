<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RentalStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $rental;
    public $oldStatus;

    public function __construct($rental, $oldStatus)
    {
        $this->rental = $rental;
        $this->oldStatus = $oldStatus;
    }

    public function build()
    {
        $statusText = [
            'confirmed' => 'confirmée',
            'rejected' => 'refusée',
            'cancelled' => 'annulée'
        ];

        $message = $statusText[$this->rental->status] ?? 'mise à jour';

        return $this->subject("ATLAS AND CO - Votre demande de location a été {$message}")
                    ->view('emails.rental-status-updated');
    }
}
