<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    protected $fillable = ['name', 'base_fare', 'per_km_rate', 'capacity'];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
