<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTripRequested extends Notification
{
    use Queueable;

    public $trip;

    public function __construct($trip)
    {
        $this->trip = $trip;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('🚀 Nouvelle Demande de Course - ATLAS AND CO')
                    ->greeting('Bonjour Admin,')
                    ->line('Une nouvelle demande de course vient d\'être soumise sur ATLAS AND CO.')
                    ->line('**Détails de la course :**')
                    ->line('Client : ' . $this->trip->client->name)
                    ->line('Départ : ' . $this->trip->pickup_address)
                    ->line('Destination : ' . $this->trip->dropoff_address)
                    ->line('Prix estimé : ' . $this->trip->price . ' €')
                    ->action('Accéder au Dashboard pour Assigner', url('/admin/dashboard'))
                    ->line('Merci de traiter cette demande rapidement pour garantir l\'excellence de notre service.');
    }
}
