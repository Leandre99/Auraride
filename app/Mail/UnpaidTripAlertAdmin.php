<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Trip;

class UnpaidTripAlertAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $trip;
    public $hours;

    public function __construct(Trip $trip, int $hours)
    {
        $this->trip = $trip;
        $this->hours = $hours;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Atlas Taxi / VTC — Course non payée depuis +24h',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.unpaid-trip-alert-admin',
        );
    }
}
