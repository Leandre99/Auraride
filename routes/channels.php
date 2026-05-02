<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('admin', function ($user) {
    return $user->role === 'admin';
});

Broadcast::channel('drivers.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id && $user->role === 'driver';
});

Broadcast::channel('drivers', function ($user) {
    return $user->role === 'driver';
});

Broadcast::channel('trip.{tripId}', function ($user, $tripId) {
    $trip = \App\Models\Trip::find($tripId);
    if (!$trip) return false;
    return $user->id === $trip->client_id || $user->id === $trip->driver_id;
});
