<?php

namespace App\Events;

use App\Models\Trip;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Événement domaine « nouvelle course » (sans broadcast obligatoire).
 * Le tableau admin se met à jour par rechargement / navigation.
 */
class TripRequested
{
    use Dispatchable, SerializesModels;

    public function __construct(public Trip $trip)
    {
    }
}
