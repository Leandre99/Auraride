<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\User;
use App\Models\VehicleType;
use App\Notifications\NewRentalRequested;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class RentalController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'pickup_time' => 'required',
            'with_driver' => 'nullable|boolean',
        ]);

        // Simple price calculation for demo (150-450 per day)
        $vehicleType = VehicleType::find($request->vehicle_type_id);
        $days = (strtotime($request->end_date) - strtotime($request->start_date)) / (60 * 60 * 24) + 1;
        
        // Mock daily prices
        $dailyPrices = [1 => 150, 2 => 300, 3 => 450];
        $pricePerDay = $dailyPrices[$vehicleType->id] ?? 200;
        $totalPrice = $days * $pricePerDay;

        $rental = Rental::create([
            'user_id' => auth()->id(),
            'vehicle_type_id' => $request->vehicle_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'pickup_time' => $request->pickup_time,
            'with_driver' => $request->boolean('with_driver'),
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        $rental->load(['user', 'vehicleType']);

        $admins = User::where('role', 'admin')->get();
        try {
            Notification::send($admins, new NewRentalRequested($rental));
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json(['success' => true, 'rental' => $rental]);
    }
}
