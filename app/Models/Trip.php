<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $table = 'trips';

    protected $fillable = [
        'client_id',
        'driver_id',
        'vehicle_id',
        'vehicle_type_id',
        'status',
        'pickup_address',
        'dropoff_address',
        'pickup_lat',
        'pickup_lng',
        'dropoff_lat',
        'dropoff_lng',
        'distance',
        'price',
        'payment_status',
        'payment_method',
        'rating',
        'comment',
    ];

    protected $casts = [
        'pickup_lat' => 'float',
        'pickup_lng' => 'float',
        'dropoff_lat' => 'float',
        'dropoff_lng' => 'float',
        'distance' => 'float',
        'duration' => 'integer',
        'price' => 'float',
        'rating' => 'integer',
    ];

    // Relation avec le client
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Relation avec le chauffeur
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    // Relation avec le véhicule
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    // Relation avec le type de véhicule
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    // Vérifier si la course est en attente
    public function isPending()
    {
        return $this->status === 'pending';
    }

    // Vérifier si la course est assignée
    public function isAssigned()
    {
        return $this->status === 'assigned';
    }

    // Vérifier si la course est acceptée
    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    // Vérifier si la course est en cours
    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    // Vérifier si la course est terminée
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    // Vérifier si la course est annulée
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }
}
