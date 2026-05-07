<?php
// app/Models/Rental.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rental extends Model
{
    protected $table = 'rentals';

    protected $fillable = [
        'user_id',
        'vehicle_type_id',
        'start_date',
        'end_date',
        'pickup_time',
        'with_driver',
        'delivery_address',
        'certifies_license',
        'daily_price',
        'driver_fee_per_day',
        'total_days',
        'total_price',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'with_driver' => 'boolean',
        'certifies_license' => 'boolean',
        'total_price' => 'decimal:2',
        'daily_price' => 'decimal:2',
        'driver_fee_per_day' => 'decimal:2'
    ];

    // Relation avec l'utilisateur
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec le type de véhicule
    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }

    // Statuts disponibles
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
