<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

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

        $mail = $this->subject("ATLAS TAXI / VTC - Votre demande de location a été {$message}")
                     ->view('emails.rental-status-updated');

        if (in_array($this->newStatus, ['confirmed', 'completed'])) {
            $taxRate = 0.10;
            $totalAmount = $this->rental->total_price ?? 0;
            $netAmount = $totalAmount / (1 + $taxRate);
            $taxAmount = $totalAmount - $netAmount;
            $description = 'Location: N/A - N/A';

            $data = [
                'client' => $this->rental->user,
                'clientName' => 'Client',
                'invoiceNumber' => 'INV-LOC-' . strtoupper(Str::random(8)),
                'date' => $this->rental->created_at,
                'description' => $description,
                'netAmount' => $netAmount,
                'taxAmount' => $taxAmount,
                'totalAmount' => $totalAmount,
            ];

            $pdf = Pdf::loadView('pdf.invoice', $data);

            $mail->attachData($pdf->output(), 'Facture-Location-' . $this->rental->id . '.pdf', [
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
}
