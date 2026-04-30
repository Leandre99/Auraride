<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Trip;
use Illuminate\Http\Request;

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

        return view('admin.dashboard', compact('stats', 'recentTrips'));
    }

    public function users(Request $request)
    {
        $role = $request->get('role');
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
        $trips = Trip::with(['client', 'driver', 'vehicle.type'])->latest()->paginate(20);
        return view('admin.trips.index', compact('trips'));
    }

    public function cancelTrip(Trip $trip)
    {
        if (in_array($trip->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Cette course est déjà terminée ou annulée.');
        }

        $trip->update(['status' => 'cancelled']);
        return back()->with('success', 'La course a été annulée par l\'administrateur.');
    }
}
