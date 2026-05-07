<?php
// app/Http/Controllers/RentalController.php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\RentalConfirmationClient;
use App\Mail\RentalNotificationAdmin;

class RentalController extends Controller
{

    public function store(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'pickup_time' => 'required',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'with_driver' => 'sometimes|boolean',
            'delivery_address' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $vehicleType = VehicleType::find($request->vehicle_type_id);

        // 2. Calcul
        $start = new \DateTime($request->start_date);
        $end = new \DateTime($request->end_date);
        $days = $start->diff($end)->days + 1;

        // Prix journalier selon le type de véhicule
        $dailyPrice = $vehicleType->daily_price; // Utilise la méthode du modèle

        $driverFee = $request->has('with_driver') ? 150 : 0;
        $totalPrice = ($dailyPrice + $driverFee) * $days;

        // 3. Création dans la table rentals
        $rental = Rental::create([
            'user_id' => $user->id,
            'vehicle_type_id' => $request->vehicle_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'pickup_time' => $request->pickup_time,
            'with_driver' => $request->has('with_driver'),
            'delivery_address' => $request->delivery_address,
            'daily_price' => $dailyPrice,
            'driver_fee_per_day' => $driverFee,
            'total_days' => $days,
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        // 4. Email au client
        Mail::to($user->email)->send(new RentalConfirmationClient($rental, $user, $vehicleType));

        // 5. Email à l'admin
        $adminEmail = config('mail.admin_email', 'admin@atlasandco.com');
        Mail::to($adminEmail)->send(new RentalNotificationAdmin($rental, $user, $vehicleType));

        // 6. Retour JSON
        return response()->json([
            'success' => true,
            'message' => 'Votre demande de location a bien été transmise. Vous allez recevoir un email récapitulatif. Nos équipes vous contacteront dans les plus brefs délais.',
            'rental_id' => $rental->id
        ]);
    }

    // Optionnel : voir l'historique des locations de l'utilisateur
    public function myRentals()
    {
        $rentals = Rental::where('user_id', Auth::id())
                        ->with('vehicleType')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        return view('user.rentals', compact('rentals'));
    }
}
