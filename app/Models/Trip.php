<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'client_id', 'driver_id', 'vehicle_id', 'status',
        'pickup_address', 'dropoff_address',
        'pickup_lat', 'pickup_lng', 'dropoff_lat', 'dropoff_lng',
        'distance', 'duration', 'price',
        'payment_status', 'payment_method', 'rating', 'comment'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
