<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_type_id',
        'start_date',
        'end_date',
        'pickup_time',
        'with_driver',
        'total_price',
        'status',
    ];

    protected $casts = [
        'with_driver' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }
}
