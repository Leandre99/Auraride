<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = ['user_id', 'vehicle_type_id', 'model', 'plate_number', 'color'];

    public function driver()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    /** Alias de compatibilité: certains écrans utilisent `vehicle.type`. */
    public function type()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
