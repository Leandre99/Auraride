<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\VehicleType;
use App\Models\User;
use App\Events\TripRequested;
use App\Events\TripAccepted;
use Illuminate\Support\Facades\Mail;
use App\Mail\TripConfirmationClient;
use App\Mail\TripNotificationAdmin;
use Illuminate\Support\Facades\Log;

class TripController extends Controller
{
    public function estimate(Request $request)
    {
        $request->validate([
            'pickup_lat' => 'required|numeric|between:-90,90',
            'pickup_lng' => 'required|numeric|between:-180,180',
            'dropoff_lat' => 'required|numeric|between:-90,90',
            'dropoff_lng' => 'required|numeric|between:-180,180',
        ]);

        $distance = $this->calculateDistance(
            $request->pickup_lat,
            $request->pickup_lng,
            $request->dropoff_lat,
            $request->dropoff_lng
        );

        // Distance minimum de 0.5 km pour éviter les prix aberrants
        $distance = max($distance, 0.5);

        $allowed = ['Berline Standard', 'Van Luxe', 'Sprinter Mercedes'];
        $vehicleTypes = VehicleType::query()
            ->whereIn('name', $allowed)
            ->get()
            ->map(function ($type) use ($distance) {
                $price = $type->base_fare + ($type->per_km_rate * $distance);
                $price = max($price, 8.00); // Prix minimum 8€

                return [
                    'id' => $type->id,
                    'name' => $type->name,
                    'price' => round($price, 2),
                    'distance' => round($distance, 2),
                    'duration' => round($distance * 2, 0), // ~2 min/km
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
        ]);

        $client = auth()->user();
        $vehicleType = VehicleType::find($request->vehicle_type_id);

        // Création du trajet
        $trip = Trip::create([
            'client_id' => $client->id,
            'vehicle_type_id' => $request->vehicle_type_id,
            'status' => 'pending',
            'pickup_address' => $request->pickup_address,
            'dropoff_address' => $request->dropoff_address,
            'pickup_lat' => $request->pickup_lat,
            'pickup_lng' => $request->pickup_lng,
            'dropoff_lat' => $request->dropoff_lat,
            'dropoff_lng' => $request->dropoff_lng,
            'price' => $request->price,
            'distance' => $request->distance,
            'payment_status' => 'pending',
        ]);

        // Charger la relation client pour les notifications
        $trip->load('client');

        // ========== ENVOI DES EMAILS ==========

        // 1. Email au client (confirmation)
        try {
            Mail::to($client->email)->send(new TripConfirmationClient($trip, $client, $vehicleType));
            Log::info('Email client envoyé pour le trajet #' . $trip->id);
        } catch (\Exception $e) {
            Log::error('Erreur envoi email client : ' . $e->getMessage());
        }

        // 2. Email aux admins (notification)
        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new TripNotificationAdmin($trip, $client, $vehicleType));
            }
            Log::info('Email admin envoyé pour le trajet #' . $trip->id);
        } catch (\Exception $e) {
            Log::error('Erreur envoi email admin : ' . $e->getMessage());
        }

        // ========== NOTIFICATIONS EXISTANTES ==========

        // Notification via base de données
        try {
            $admins = User::where('role', 'admin')->get();
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewTripRequested($trip));
        } catch (\Throwable $e) {
            Log::error('Erreur notification DB : ' . $e->getMessage());
        }

        // Événement WebSocket
        try {
            event(new TripRequested($trip));
        } catch (\Throwable $e) {
            Log::error('Erreur événement WebSocket : ' . $e->getMessage());
        }

        return response()->json($trip->fresh(['client']));
    }

    public function assign(Request $request, Trip $trip)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
        ]);

        $driver = User::find($request->driver_id);

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
        if ($trip->status !== 'assigned' || $trip->driver_id !== auth()->id()) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Cette course ne vous est pas assignée ou n\'est plus disponible.'], 422);
            }
            return redirect()->back()->with('error', 'Cette course ne vous est pas assignée ou n\'est plus disponible.');
        }

        $trip->update(['status' => 'accepted']);

        try {
            event(new TripAccepted($trip));
        } catch (\Throwable $e) {
            report($e);
        }

        if (request()->expectsJson()) {
            return response()->json($trip);
        }

        return redirect()->back()->with('success', 'Course acceptée.');
    }

    public function start(Trip $trip)
    {
        if ($trip->driver_id !== auth()->id()) {
            abort(403);
        }

        if ($trip->status !== 'accepted') {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'La course doit être acceptée avant le départ.'], 422);
            }
            return redirect()->back()->with('error', 'La course doit être acceptée avant le départ.');
        }

        $trip->update(['status' => 'in_progress']);

        if (request()->expectsJson()) {
            return response()->json($trip);
        }

        return redirect()->back()->with('success', 'Course démarrée.');
    }

    public function complete(Trip $trip)
    {
        if ($trip->driver_id !== auth()->id()) {
            abort(403);
        }

        if ($trip->status !== 'in_progress') {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'La course doit être en cours pour être terminée.'], 422);
            }
            return redirect()->back()->with('error', 'La course doit être en cours pour être terminée.');
        }

        $trip->update(['status' => 'completed']);

        if (request()->expectsJson()) {
            return response()->json($trip);
        }

        return redirect()->back()->with('success', 'Course terminée. En attente du règlement client.');
    }

    public function cancel(Trip $trip)
    {
        if ($trip->client_id !== auth()->id() && $trip->driver_id !== auth()->id()) {
            abort(403);
        }

        $trip->update(['status' => 'cancelled']);

        if (request()->expectsJson()) {
            return response()->json($trip);
        }

        return redirect()->back()->with('success', 'La course a été annulée.');
    }

    public function rate(Request $request, Trip $trip)
    {
        if ($trip->client_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
            'payment_method' => 'nullable|in:card,cash',
        ]);

        $trip->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'payment_method' => $request->input('payment_method', 'cash'),
            'payment_status' => 'pending',
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Merci pour votre avis.');
    }

    public function confirmPayment(Trip $trip)
    {
        if ($trip->driver_id !== auth()->id()) {
            abort(403);
        }

        $trip->update(['payment_status' => 'paid']);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Paiement confirmé.');
    }

    /**
     * Calcul de la distance réelle entre deux points GPS (formule Haversine)
     * Retourne la distance en kilomètres
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Rayon de la Terre en km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2); // Arrondi à 2 décimales
    }
}
