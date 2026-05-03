<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Trip;
use App\Models\VehicleType;

use App\Events\TripRequested;
use App\Events\TripAccepted;

class TripController extends Controller
{
    public function estimate(Request $request)
    {
        $request->validate([
            'pickup_lat' => 'required',
            'pickup_lng' => 'required',
            'dropoff_lat' => 'required',
            'dropoff_lng' => 'required',
        ]);

        // Mock distance calculation (Haversine or simple Euclidean for demo)
        // In a real app, use OSRM or Google Maps API here.
        $distance = $this->calculateMockDistance(
            $request->pickup_lat, $request->pickup_lng,
            $request->dropoff_lat, $request->dropoff_lng
        );

        $vehicleTypes = VehicleType::all()->map(function ($type) use ($distance) {
            $price = $type->base_fare + ($type->per_km_rate * $distance);
            return [
                'id' => $type->id,
                'name' => $type->name,
                'price' => round($price, 2),
                'distance' => round($distance, 2),
                'duration' => round($distance * 2, 0), // Mock 2 mins per km
            ];
        });

        return response()->json($vehicleTypes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'pickup_address' => 'required|string',
            'dropoff_address' => 'required|string',
            'pickup_lat' => 'required',
            'pickup_lng' => 'required',
            'dropoff_lat' => 'required',
            'dropoff_lng' => 'required',
            'price' => 'required|numeric',
            'distance' => 'required|numeric',
            'duration' => 'required|numeric',
        ]);

        $trip = Trip::create([
            'client_id' => auth()->id(),
            'status' => 'pending',
            'pickup_address' => $request->pickup_address,
            'dropoff_address' => $request->dropoff_address,
            'pickup_lat' => $request->pickup_lat,
            'pickup_lng' => $request->pickup_lng,
            'dropoff_lat' => $request->dropoff_lat,
            'dropoff_lng' => $request->dropoff_lng,
            'price' => $request->price,
            'distance' => $request->distance,
            'duration' => $request->duration,
        ]);

        // Load client for the notification
        $trip->load('client');

        $admins = \App\Models\User::where('role', 'admin')->get();
        try {
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewTripRequested($trip));
        } catch (\Throwable $e) {
            report($e);
        }
        try {
            event(new TripRequested($trip));
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json($trip->fresh(['client']));
    }

    public function assign(Request $request, Trip $trip)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
        ]);

        $driver = \App\Models\User::find($request->driver_id);
        
        if ($driver->role !== 'driver') {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'L\'utilisateur sélectionné n\'est pas un chauffeur.'], 422);
            }

            return back()->with('error', 'L\'utilisateur sélectionné n\'est pas un chauffeur.');
        }

        $trip->update([
            'driver_id' => $driver->id,
            'vehicle_id' => $driver->vehicle?->id,
            'status' => 'assigned',
        ]);

        try {
            event(new \App\Events\TripAssigned($trip));
        } catch (\Throwable $e) {
            report($e);
        }

        $trip->load(['client', 'driver', 'vehicle']);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'trip' => $trip]);
        }

        return back()->with('success', 'La course a été assignée au chauffeur.');
    }

    public function accept(Trip $trip)
    {
        // Now driver accepts a trip assigned to them
        if ($trip->status !== 'assigned' || $trip->driver_id !== auth()->id()) {
            return response()->json(['error' => 'Cette course ne vous est pas assignée ou n\'est plus disponible.'], 422);
        }

        $trip->update([
            'status' => 'accepted',
        ]);

        try {
            event(new TripAccepted($trip));
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json($trip);
    }

    public function start(Trip $trip)
    {
        if ($trip->driver_id !== auth()->id()) {
            abort(403);
        }

        $trip->update(['status' => 'in_progress']);

        return response()->json($trip);
    }

    public function complete(Trip $trip)
    {
        if ($trip->driver_id !== auth()->id()) {
            abort(403);
        }

        $trip->update(['status' => 'completed']);

        // TODO: Trigger payment

        return response()->json($trip);
    }

    public function cancel(Trip $trip)
    {
        // Both client and driver can cancel for now
        if ($trip->client_id !== auth()->id() && $trip->driver_id !== auth()->id()) {
            abort(403);
        }

        $trip->update(['status' => 'cancelled']);

        return response()->json($trip);
    }

    public function rate(Request $request, Trip $trip)
    {
        if ($trip->client_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $trip->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'payment_method' => 'cash', // Default to cash as per requirements
            'payment_status' => 'pending', // Payment will be confirmed by driver/terminal
        ]);

        return response()->json(['success' => true]);
    }

    public function confirmPayment(Trip $trip)
    {
        if ($trip->driver_id !== auth()->id()) {
            abort(403);
        }

        $trip->update(['payment_status' => 'paid']);

        return response()->json(['success' => true]);
    }

    private function calculateMockDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return $miles * 1.609344; // Convert to KM
    }
}
