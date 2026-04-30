<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $trip;
    public $lat;
    public $lng;

    public function __construct($trip, $lat, $lng)
    {
        $this->trip = $trip;
        $this->lat = $lat;
        $this->lng = $lng;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('trip.' . $this->trip->id),
        ];
    }
}
