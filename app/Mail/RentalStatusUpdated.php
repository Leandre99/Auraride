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
    public $newStatus;

    public function __construct($rental, $oldStatus, $newStatus = null)
    {
        $this->rental = $rental;
        $this->oldStatus = $oldStatus;
        // Si $newStatus n'est pas fourni, on utilise le statut actuel du rental
        $this->newStatus = $newStatus ?? $rental->status;
    }

    public function build()
    {
        $statusText = [
            'pending' => 'en attente',
            'confirmed' => 'confirmée',
            'rejected' => 'refusée',
            'completed' => 'terminée',
            'cancelled' => 'annulée'
        ];

        $message = $statusText[$this->newStatus] ?? 'mise à jour';

        return $this->subject("ATLAS AND CO - Votre demande de location a été {$message}")
                    ->view('emails.rental-status-updated');
    }
}
