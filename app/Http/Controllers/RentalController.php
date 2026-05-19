<?php
// app/Http/Controllers/RentalController.php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Trip;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\RentalConfirmationClient;
use App\Mail\RentalNotificationAdmin;
use App\Models\ActivityLog;
use Illuminate\Pagination\LengthAwarePaginator;

class RentalController extends Controller
{

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date',
                'pickup_time' => 'required',
                'vehicle_type_id' => 'required|exists:vehicle_types,id',
                'with_driver' => 'sometimes',
                'delivery_address' => 'nullable|string|max:500',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides : ' . implode(' ', \Illuminate\Support\Arr::flatten($e->errors())),
                'errors' => $e->errors()
            ], 422);
        }

        $user = Auth::user();
        $vehicleType = VehicleType::find($request->vehicle_type_id);

        if (!$vehicleType) {
            return response()->json(['success' => false, 'message' => 'Type de véhicule introuvable.'], 404);
        }

        // 2. Calcul des jours et prix
        try {
            $start = new \DateTime($request->start_date);
            $end = new \DateTime($request->end_date);
            $interval = $start->diff($end);
            $days = $interval->days + 1;

            // Prix journalier
            $dailyPrice = (float) ($vehicleType->daily_price ?? 100);
            $withDriver = $request->has('with_driver') && $request->with_driver == '1';
            $driverFee = $withDriver ? 150.0 : 0.0;
            $totalPrice = ($dailyPrice + $driverFee) * $days;

            // 3. Création de la location
            $rental = Rental::create([
                'user_id' => $user->id,
                'vehicle_type_id' => $request->vehicle_type_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'pickup_time' => $request->pickup_time,
                'with_driver' => $withDriver,
                'delivery_address' => $request->delivery_address,
                'daily_price' => $dailyPrice,
                'driver_fee_per_day' => $driverFee,
                'total_days' => $days,
                'total_price' => $totalPrice,
                'status' => 'pending'
            ]);

            // 4. Log et Notifications (en mode asynchrone)
            try {
                ActivityLog::log('rental_requested', "Le client {$user->name} a fait une demande de location pour un(e) {$vehicleType->name} (#{$rental->id})", $rental);

                // SMS au client
                if (!empty($user->phone_number)) {
                    $msgClient = "ATLAS VTC: Votre demande de location pour un(e) {$vehicleType->name} a bien été enregistrée. Nous la traitons rapidement.";
                    \App\Jobs\SendSmsJob::dispatch($user->phone_number, $msgClient);
                }

                // SMS aux admins
                $admins = \App\Models\User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    if (!empty($admin->phone_number)) {
                        \App\Jobs\SendSmsJob::dispatch($admin->phone_number, "NOUVELLE LOCATION (ATLAS VTC): {$user->name} souhaite louer un(e) {$vehicleType->name} du " . \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') . " au " . \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') . ".");
                    }
                }
            } catch (\Exception $smsEx) {
                \Illuminate\Support\Facades\Log::warning("Erreur SMS/log lors d'une location : " . $smsEx->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Votre demande de location a été transmise avec succès.',
                'rental_id' => $rental->id
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Erreur critique lors de la création d'une location : " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur technique est survenue. Veuillez réessayer ou contacter le support.'
            ], 500);
        }
    }

    // Voir l'historique des réservations (VTC + Locations)
    public function myRentals(Request $request)
    {
        $user_id = Auth::id();

        $rentals = Rental::where('user_id', $user_id)
            ->with('vehicleType')
            ->get()
            ->map(function ($rental) {
                $rental->item_type = 'rental';
                return $rental;
            });

        $trips = Trip::where('client_id', $user_id)
            ->with('vehicleType')
            ->get()
            ->map(function ($trip) {
                $trip->item_type = 'trip';
                return $trip;
            });

        $merged = $rentals->concat($trips)->sortByDesc('created_at')->values();

        $page = $request->get('page', 1);
        $perPage = 10;
        $items = new LengthAwarePaginator(
            $merged->forPage($page, $perPage),
            $merged->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('client.historique', compact('items'));
    }
}
