<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Trip;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\RentalStatusUpdated;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'users_count' => User::count(),
            'active_trips' => Trip::whereIn('status', ['accepted', 'in_progress'])->count(),
            'total_revenue' => Trip::where('status', 'completed')->sum('price'),
            'trips_today' => Trip::whereDate('created_at', now()->today())->count(),
        ];

        $recentTrips = Trip::with(['client', 'driver'])->latest()->take(10)->get();

        // Pending trips that need assignment
        $pendingTrips = Trip::with('client')->where('status', 'pending')->latest()->get();

        /* Chauffeurs éligibles : approuvé + actif ; certains environnements n'ont pas encore de véhicule lié. */
        $drivers = User::where('role', 'driver')
            ->where('is_approved', true)
            ->where('is_active', true)
            ->with('vehicle')
            ->orderBy('name')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTrips', 'pendingTrips', 'drivers'));
    }

    public function users(Request $request)
    {
        $role = $request->input('role');
        $query = User::query();

        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas suspendre votre propre compte.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activé' : 'suspendu';

        return back()->with('success', "Le compte de {$user->name} a été {$status}.");
    }

    public function approveDriver(User $user)
    {
        if ($user->role !== 'driver') {
            return back()->with('error', 'Cet utilisateur n\'est pas un chauffeur.');
        }

        $user->update(['is_approved' => true]);

        return back()->with('success', "Le chauffeur {$user->name} a été approuvé.");
    }

    public function trips()
    {
        $trips = Trip::with(['client', 'driver', 'vehicle.vehicleType'])->latest()->paginate(20);
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
        return back()->with('success', 'La course a été annulée par l\'administrateur.');
    }

    // ========== GESTION DES LOCATIONS ==========

    /**
     * Afficher toutes les demandes de location
     */
    public function rentals()
    {
        $rentals = Rental::with(['user', 'vehicleType'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

        return view('admin.rentals.index', compact('rentals'));
    }

    /**
     * Afficher le formulaire d'édition d'une demande de location
     */
    public function editRental($id)
    {
        $rental = Rental::with(['user', 'vehicleType'])->findOrFail($id);

        return view('admin.rentals.edit', compact('rental'));
    }

    /**
     * Mettre à jour le statut d'une demande de location
     */
    public function updateRentalStatus(Request $request, $id)
    {
        $rental = Rental::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,rejected,cancelled',
            'admin_notes' => 'nullable|string'
        ]);

        $oldStatus = $rental->status;
        $rental->status = $request->status;
        $rental->admin_notes = $request->admin_notes;
        $rental->save();

        // Envoyer un email au client pour l'informer du changement de statut
        try {
            Mail::to($rental->user->email)->send(new RentalStatusUpdated($rental, $oldStatus));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email statut location : ' . $e->getMessage());
        }

        $statusMessages = [
            'confirmed' => 'confirmée',
            'rejected' => 'refusée',
            'cancelled' => 'annulée',
            'pending' => 'remise en attente'
        ];

        $message = $statusMessages[$request->status] ?? 'mise à jour';

        return redirect()->route('admin.rentals')->with('success', "La demande de location a été {$message}.");
    }
}
