<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewRentalRequested extends Notification
{
    use Queueable;

    public $rental;

    public function __construct($rental)
    {
        $this->rental = $rental;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('🔑 Nouvelle Demande de Location - ATLAS AND CO')
                    ->greeting('Bonjour Admin,')
                    ->line('Une nouvelle demande de location de véhicule a été soumise.')
                    ->line('**Détails de la location :**')
                    ->line('Client : ' . $this->rental->user->name)
                    ->line('Véhicule : ' . $this->rental->vehicleType->name)
                    ->line('Du : ' . $this->rental->start_date)
                    ->line('Au : ' . $this->rental->end_date)
                    ->line('Heure de prise en charge : ' . $this->rental->pickup_time)
                    ->action('Gérer les Locations', url('/admin/dashboard'))
                    ->line('Merci de contacter le client pour finaliser la location.');
    }
}
