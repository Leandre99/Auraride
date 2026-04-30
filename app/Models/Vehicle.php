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

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
