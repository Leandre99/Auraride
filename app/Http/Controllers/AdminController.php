<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Trip;
use App\Models\Rental;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\RentalStatusUpdated;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'users_count' => User::count(),
            'pending_trips_count' => Trip::where('status', 'pending')->count(),
            'total_revenue' => Trip::where('status', 'completed')->sum('price') + Rental::whereIn('status', ['confirmed', 'completed'])->sum('total_price'),
            'activities_today' => Trip::whereDate('created_at', now()->today())->count() + Rental::whereDate('created_at', now()->today())->count(),
            'pending_rentals_count' => Rental::where('status', 'pending')->count(),
        ];

        $recentTrips = Trip::with(['client', 'driver'])->latest()->take(5)->get();
        $pendingRentals = Rental::with(['user', 'vehicleType'])->where('status', 'pending')->latest()->take(5)->get();
        $pendingTrips = Trip::with('client')->where('status', 'pending')->latest()->get();

        $drivers = User::where('role', 'driver')
            ->where('is_approved', true)
            ->where('is_active', true)
            ->with('vehicle')
            ->orderBy('name')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTrips', 'pendingTrips', 'drivers', 'pendingRentals'));
    }

    public function users(Request $request)
    {
        $role = $request->input('role');
        $query = User::query();

        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas suspendre votre propre compte.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activé' : 'suspendu';

        ActivityLog::log('user_toggle_active', "L'admin a {$status} le compte de {$user->name} ({$user->email})", $user);

        return back()->with('success', "Le compte de {$user->name} a été {$status}.");
    }

    public function approveDriver(User $user)
    {
        if ($user->role !== 'driver') {
            return back()->with('error', 'Cet utilisateur n\'est pas un chauffeur.');
        }

        $user->update(['is_approved' => true]);

        ActivityLog::log('driver_approved', "L'admin a approuvé le chauffeur {$user->name}", $user);

        return back()->with('success', "Le chauffeur {$user->name} a été approuvé.");
    }

    public function trips()
    {
        $trips = Trip::with(['client', 'driver', 'vehicle.vehicleType'])->latest()->paginate(10);
        $drivers = User::where('role', 'driver')
            ->where('is_approved', true)
            ->where('is_active', true)
            ->with('vehicle')
            ->orderBy('name')
            ->get();

        return view('admin.trips.index', compact('trips', 'drivers'));
    }

    public function cancelTrip(Trip $trip)
    {
        if (in_array($trip->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Cette course est déjà terminée ou annulée.');
        }

        $trip->update(['status' => 'cancelled']);

        ActivityLog::log('trip_cancelled_admin', "L'admin a annulé la course #{$trip->id}", $trip);

        return back()->with('success', 'La course a été annulée par l\'administrateur.');
    }
    public function rentals()
    {
        $rentals = Rental::with(['user', 'vehicleType'])
            ->latest()
            ->paginate(10);

        return view('admin.rentals.index', compact('rentals'));
    }
    public function editRental($id)
    {
        $rental = Rental::with(['user', 'vehicleType'])->findOrFail($id);
        $drivers = User::where('role', 'driver')->where('is_approved', true)->get();

        return view('admin.rentals.edit', compact('rental', 'drivers'));
    }
    public function updateRentalStatus(Request $request, $id)
    {
        $rental = Rental::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,rejected,completed,cancelled',
            'admin_notes' => 'nullable|string',
            'driver_id' => 'nullable|exists:users,id'
        ]);

        $oldStatus = $rental->status;
        $rental->status = $request->status;
        $rental->admin_notes = $request->admin_notes;
        $rental->driver_id = $request->driver_id;
        $rental->save();

        ActivityLog::log('rental_status_updated', "L'admin a mis à jour le statut de la location #{$rental->id} de {$oldStatus} vers {$request->status}", $rental);

        if (in_array($request->status, ['confirmed', 'completed'])) {
            try {
                Mail::to($rental->user->email)->queue(new RentalStatusUpdated($rental, $oldStatus, $request->status));
                if (!empty($rental->user->phone_number)) {
                    \App\Jobs\SendSmsJob::dispatch($rental->user->phone_number, "ATLAS VTC: Votre location a été confirmée. Votre facture a été envoyée par email.");
                }
            } catch (\Exception $e) {
                \Log::error('Erreur mise en file d\'attente email : ' . $e->getMessage());
            }
        } else {
            if (!empty($rental->user->phone_number)) {
                $statusMessages = [
                    'rejected' => 'refusée',
                    'cancelled' => 'annulée',
                    'pending' => 'remise en attente'
                ];
                $statusMsg = $statusMessages[$request->status] ?? 'mise à jour';
                \App\Jobs\SendSmsJob::dispatch($rental->user->phone_number, "ATLAS VTC: Votre demande de location a été {$statusMsg}.");
            }
        }

        $statusMessages = [
            'confirmed' => 'confirmée',
            'rejected' => 'refusée',
            'cancelled' => 'annulée',
            'completed' => 'terminée',
            'pending' => 'remise en attente'
        ];

        $message = $statusMessages[$request->status] ?? 'mise à jour';

        return redirect()->route('admin.rentals')->with('success', "La demande de location a été {$message}.");
    }

    public function confirmRental(Rental $rental)
    {
        $oldStatus = $rental->status;
        $rental->update(['status' => 'confirmed']);

        ActivityLog::log('rental_approved', "L'admin a confirmé la demande de location #{$rental->id}", $rental);

        try {
            Mail::to($rental->user->email)->queue(new RentalStatusUpdated($rental, $oldStatus));
        } catch (\Exception $e) {
        }

        return back()->with('success', 'La location a été confirmée.');
    }

    public function rejectRental(Rental $rental)
    {
        $oldStatus = $rental->status;
        $rental->update(['status' => 'rejected']);

        ActivityLog::log('rental_rejected', "L'admin a rejeté la demande de location #{$rental->id}", $rental);

        try {
            Mail::to($rental->user->email)->queue(new RentalStatusUpdated($rental, $oldStatus));
        } catch (\Exception $e) {
        }

        return back()->with('success', 'La location a été rejetée.');
    }

    public function logs()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(10);
        return view('admin.logs.index', compact('logs'));
    }
}
