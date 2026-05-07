<?php
// app/Models/VehicleType.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    protected $table = 'vehicle_types';
    protected $appends = ['daily_price'];

    protected $fillable = [
        'name',
        'base_fare',
        'per_km_rate',
        'capacity'
    ];

    // Prix journalier pour la location (à définir selon tes tarifs)
    // Tu peux ajouter une colonne daily_price ou utiliser une mapping
    public function getDailyPriceAttribute()
    {
        // Mapping temporaire selon le type de véhicule
        $prices = [
            'Berline Standard' => 100,
            'Van Luxe' => 300,
            'Sprinter' => 350
        ];

        return $prices[$this->name] ?? $this->base_fare;
    }
}
