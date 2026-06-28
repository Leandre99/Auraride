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
use App\Models\ActivityLog;
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

        $distance = $this->getRouteDistance(
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
                $priceHT = $type->base_fare + ($type->per_km_rate * $distance);
                $priceHT = max($priceHT, 8.00); // Prix minimum HT 8€

                $tva = $priceHT * 0.10; // TVA 10% pour le transport
                $priceTTC = $priceHT + $tva;

                return [
                    'id' => $type->id,
                    'name' => $type->name,
                    'price_ht' => round($priceHT, 2),
                    'tva' => round($tva, 2),
                    'price_ttc' => round($priceTTC, 2),
                    'price' => round($priceTTC, 2), // Alias pour compatibilité
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
            'scheduled_at' => 'nullable|date|after_or_equal:now',
        ]);

        $client = auth()->user();

        if (!$client) {
            $request->validate([
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'required|string|email|max:255',
                'guest_phone' => 'required|string|max:20',
            ]);

            // Formater le téléphone
            $phoneNumber = preg_replace('/[\s.\-()]/', '', $request->guest_phone);

            // Trouver ou créer le compte client invité
            $client = User::where('phone_number', $phoneNumber)
                          ->orWhere('email', $request->guest_email)
                          ->first();

            if (!$client) {
                $client = User::create([
                    'name' => $request->guest_name,
                    'email' => $request->guest_email,
                    'phone_number' => $phoneNumber,
                    'role' => 'client',
                    'password' => bcrypt(\Illuminate\Support\Str::random(16)),
                ]);
            }

            // Connecter l'utilisateur automatiquement
            auth()->login($client);
        }
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
            'scheduled_at' => $request->scheduled_at,
        ]);

        // Charger la relation client pour les notifications
        $trip->load('client');

        // ========== ENVOI DES NOTIFICATIONS (SMS EN ARRIÈRE-PLAN) ==========
        // 1. SMS au client
        if (!empty($client->phone_number)) {
            $msgClient = "ATLAS VTC: Votre demande de course a bien été reçue. Départ: " . \Illuminate\Support\Str::limit($trip->pickup_address, 30);
            if ($trip->scheduled_at) {
                $msgClient .= " prévue le " . \Carbon\Carbon::parse($trip->scheduled_at)->format('d/m/Y H:i');
            }
            \App\Jobs\SendSmsJob::dispatch($client->phone_number, $msgClient);
        }

        // 2. SMS aux admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            if (!empty($admin->phone_number)) {
                \App\Jobs\SendSmsJob::dispatch($admin->phone_number, "NOUVELLE COURSE (ATLAS VTC): De " . \Illuminate\Support\Str::limit($trip->pickup_address, 15) . " vers " . \Illuminate\Support\Str::limit($trip->dropoff_address, 15) . " (" . $trip->price . "€)");
            }
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

        ActivityLog::log('trip_requested', "Le client {$client->name} a demandé une course de {$request->pickup_address} vers {$request->dropoff_address} (#{$trip->id})", $trip);

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

        // Vérification si le chauffeur a déjà une course en cours
        $activeTrip = Trip::where('driver_id', $driver->id)
            ->whereIn('status', ['assigned', 'accepted', 'in_progress'])
            ->exists();

        if ($activeTrip) {
            $msg = "Le chauffeur {$driver->name} a déjà une course en cours et ne peut pas être assigné à une nouvelle mission tant qu'il n'a pas terminé.";
            if ($request->wantsJson()) {
                return response()->json(['error' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $trip->update([
            'driver_id' => $driver->id,
            'vehicle_id' => $driver->vehicle?->id,
            'status' => 'assigned',
        ]);

        $trip->load(['client', 'driver', 'vehicle']);

        if (!empty($trip->client->phone_number)) {
            $dateTxt = $trip->scheduled_at ? 'prévue le ' . \Carbon\Carbon::parse($trip->scheduled_at)->format('d/m/Y à H:i') : 'immédiate';
            \App\Jobs\SendSmsJob::dispatch($trip->client->phone_number, "ATLAS VTC: Un chauffeur ({$driver->name}) a été assigné à votre course {$dateTxt}.");
        }
        
        if (!empty($driver->phone_number)) {
            \App\Jobs\SendSmsJob::dispatch($driver->phone_number, "ATLAS VTC: Une nouvelle course vous a été assignée. Veuillez consulter votre application.");
        }

        try {
            event(new \App\Events\TripAssigned($trip));
        } catch (\Throwable $e) {
            report($e);
        }

        $trip->load(['client', 'driver', 'vehicle']);

        ActivityLog::log('trip_assigned', "La course #{$trip->id} a été assignée au chauffeur {$driver->name}", $trip);

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

        $trip->load(['client', 'driver']);
        if (!empty($trip->client->phone_number)) {
            $dateTxt = $trip->scheduled_at ? 'prévue le ' . \Carbon\Carbon::parse($trip->scheduled_at)->format('d/m/Y à H:i') : 'immédiate';
            \App\Jobs\SendSmsJob::dispatch($trip->client->phone_number, "ATLAS VTC: Le chauffeur {$trip->driver->name} a confirmé sa présence pour votre course {$dateTxt}.");
        }

        try {
            event(new TripAccepted($trip));
            ActivityLog::log('trip_accepted', "Le chauffeur {$trip->driver->name} a accepté la course #{$trip->id}", $trip);
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

        ActivityLog::log('trip_started', "Le chauffeur {$trip->driver->name} a démarré la course #{$trip->id}", $trip);
        event(new \App\Events\TripUpdated($trip));

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

        ActivityLog::log('trip_completed', "Le chauffeur {$trip->driver->name} a terminé la course #{$trip->id}", $trip);
        event(new \App\Events\TripUpdated($trip));

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

        $actor = auth()->user()->role === 'admin' ? "L'administrateur" : (auth()->user()->role === 'driver' ? "Le chauffeur" : "Le client");
        ActivityLog::log('trip_cancelled', "{$actor} a annulé la course #{$trip->id}", $trip);
        event(new \App\Events\TripUpdated($trip));

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

        $trip->load('driver');
        if ($trip->driver && !empty($trip->driver->phone_number)) {
            $stars = str_repeat('★', $request->rating) . str_repeat('☆', 5 - $request->rating);
            $msg = "ATLAS VTC: Un client vous a évalué : {$stars}.";
            if ($request->comment) {
                $msg .= " \"{$request->comment}\"";
            }
            \App\Jobs\SendSmsJob::dispatch($trip->driver->phone_number, $msg);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('my.rentals')->with('success', 'Merci pour votre avis !');
    }

    public function markPaid(Request $request, Trip $trip)
    {
        if ($trip->driver_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'payment_method' => 'required|in:cash,card',
        ]);

        $trip->update([
            'payment_status' => 'paid',
            'payment_method' => $request->payment_method,
        ]);

        $trip->load('client');
        if ($trip->client) {
            try {
                $admins = User::where('role', 'admin')->pluck('email')->toArray();
                
                // N'envoyer l'e-mail que si ce n'est pas un compte express temporaire
                if (!str_starts_with($trip->client->email, 'express_')) {
                    Mail::to($trip->client->email)
                        ->cc($admins)
                        ->queue(new \App\Mail\TripReceiptClient($trip, $trip->client));
                }

                if (!empty($trip->client->phone_number)) {
                    if (str_starts_with($trip->client->email, 'express_')) {
                        $link = route('express.complete', $trip->id);
                        $msg = "ATLAS VTC: Votre paiement de {$trip->price}€ a bien été reçu. Merci ! Retrouvez votre reçu et finalisez la création de votre compte ici : {$link}";
                    } else {
                        $msg = "ATLAS VTC: Votre paiement de {$trip->price}€ a bien été reçu. Merci ! Votre facture est disponible sur votre espace client et par email.";
                    }
                    \App\Jobs\SendSmsJob::dispatch($trip->client->phone_number, $msg);
                }
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi du reçu: ' . $e->getMessage());
            }
        }

        ActivityLog::log('payment_confirmed', "Le chauffeur {$trip->driver->name} a confirmé le paiement de la course #{$trip->id} via {$request->payment_method}", $trip);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Paiement confirmé.');
    }

    public function confirmPayment(Trip $trip)
    {
        if ($trip->driver_id !== auth()->id()) {
            abort(403);
        }

        $trip->update(['payment_status' => 'paid']);

        ActivityLog::log('payment_confirmed', "Le paiement de la course #{$trip->id} a été confirmé par le chauffeur", $trip);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Paiement confirmé.');
    }

    /**
     * Calcul de la distance via OSRM (distance réelle de route).
     * En cas d'échec ou de timeout (5 secondes), repli sur la formule Haversine.
     * Retourne la distance en kilomètres.
     */
    private function getRouteDistance($lat1, $lon1, $lat2, $lon2)
    {
        try {
            $url = sprintf(
                'https://router.project-osrm.org/route/v1/driving/%s,%s;%s,%s',
                $lon1, $lat1, $lon2, $lat2
            );

            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 5,
                CURLOPT_CONNECTTIMEOUT => 3,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_USERAGENT      => 'Auraride/1.0',
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError || $httpCode !== 200 || !$response) {
                Log::warning('OSRM unavailable, falling back to Haversine.', [
                    'curl_error' => $curlError,
                    'http_code'  => $httpCode,
                ]);
                return $this->calculateDistance($lat1, $lon1, $lat2, $lon2);
            }

            $data = json_decode($response, true);

            if (
                isset($data['routes'][0]['distance']) &&
                is_numeric($data['routes'][0]['distance'])
            ) {
                return round($data['routes'][0]['distance'] / 1000, 2);
            }

            Log::warning('OSRM response missing distance, falling back to Haversine.', [
                'response' => substr($response, 0, 200),
            ]);
        } catch (\Throwable $e) {
            Log::warning('OSRM exception, falling back to Haversine: ' . $e->getMessage());
        }

        return $this->calculateDistance($lat1, $lon1, $lat2, $lon2);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    public function showBookingPage()
    {
        return view('booking');
    }

    public function track(Trip $trip)
    {
        if ($trip->client_id !== auth()->id()) {
            abort(403);
        }

        return view('client.tracking', compact('trip'));
    }
}
