<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Trip;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class TripReceiptClient extends Mailable
{
    use Queueable, SerializesModels;

    public $trip;
    public $client;

    public function __construct(Trip $trip, User $client)
    {
        $this->trip = $trip;
        $this->client = $client;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Atlas Taxi / VTC — Reçu de votre course du ' . $this->trip->created_at->format('d/m/Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trip-receipt-client',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $taxRate = 0.10;
        $totalAmount = $this->trip->price ?? 0;
        $netAmount = $totalAmount / (1 + $taxRate);
        $taxAmount = $totalAmount - $netAmount;
        $description = 'Course: ' . ($this->trip->pickup_address ?? 'N/A') . ' - ' . ($this->trip->dropoff_address ?? 'N/A');

        $data = [
            'client' => $this->client,
            'clientName' => $this->client->name ?? 'Client',
            'invoiceNumber' => 'INV-TRP-' . strtoupper(Str::random(8)),
            'date' => $this->trip->created_at,
            'description' => $description,
            'netAmount' => $netAmount,
            'taxAmount' => $taxAmount,
            'totalAmount' => $totalAmount,
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Facture-Course-' . $this->trip->id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
