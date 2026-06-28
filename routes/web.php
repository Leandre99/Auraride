<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TripController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::post('/trips/express', [TripController::class, 'storeExpress'])->name('trips.store-express');

Route::get('/about', function () {
    return view('about'); })->name('about');
Route::get('/contact', function () {
    return view('contact'); })->name('contact');
Route::get('/tarifs', function () {
    return view('prices'); })->name('prices');
Route::get('/location', function () {
    return view('rentals.location');
})->name('location');

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'admin')
        return redirect()->route('admin.dashboard');
    if ($user->role === 'driver')
        return redirect()->route('driver.dashboard');
    return redirect()->route('client.dashboard');
})->middleware(['auth'])->name('dashboard');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Password Reset Routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Client Routes
Route::middleware(['auth', 'role:client'])->prefix('client')->group(function () {
    Route::get('/dashboard', function () {
        $now = now();
        $trackingTrip = \App\Models\Trip::query()
            ->where('client_id', auth()->id())
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($now) {
                $q->whereNull('scheduled_at')
                  ->orWhere('scheduled_at', '<=', $now->copy()->addMinutes(60));
            })
            ->where(function ($q) {
                $q->whereIn('status', ['pending', 'assigned', 'accepted', 'in_progress'])
                    ->orWhere(function ($q2) {
                        $q2->where('status', 'completed')
                            ->where(function ($q3) {
                                $q3->whereNull('rating')
                                    ->orWhere('payment_status', '!=', 'paid');
                            });
                    });
            })
            ->orderByDesc('updated_at')
            ->with(['driver', 'vehicle.vehicleType'])
            ->first();

        $scheduledTrips = \App\Models\Trip::query()
            ->where('client_id', auth()->id())
            ->where('status', 'pending')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '>', $now->copy()->addMinutes(60))
            ->orderBy('scheduled_at', 'asc')
            ->get();

        return view('client.dashboard', compact('trackingTrip', 'scheduledTrips'));
    })->name('client.dashboard');

    Route::post('/trips/estimate', [\App\Http\Controllers\TripController::class, 'estimate'])->name('trips.estimate');
    Route::post('/trips', [\App\Http\Controllers\TripController::class, 'store'])->name('trips.store');
});

// Driver Routes placeholder
Route::middleware(['auth', 'role:driver'])->prefix('driver')->group(function () {
    Route::get('/dashboard', function () {
        $driverId = auth()->id();

        // 1. Recherche d'une course VTC active (incluant celles terminées mais non payées)
        $activeTrip = \App\Models\Trip::query()
            ->where('driver_id', $driverId)
            ->where(function($q) {
                $q->whereIn('status', ['assigned', 'accepted', 'in_progress'])
                  ->orWhere(function($q2) {
                      $q2->where('status', 'completed')
                         ->where('payment_status', '!=', 'paid');
                  });
            })
            ->orderByDesc('updated_at')
            ->with(['client', 'vehicle.vehicleType'])
            ->first();

        // 2. Recherche d'une location active assignée à ce chauffeur
        $activeRental = \App\Models\Rental::query()
            ->where('driver_id', $driverId)
            ->whereIn('status', ['confirmed']) // confirmed = active pour une location
            ->orderByDesc('updated_at')
            ->with(['user', 'vehicleType'])
            ->first();

        $availableTrips = \App\Models\Trip::query()
            ->where('driver_id', $driverId)
            ->where('status', 'assigned')
            ->when($activeTrip, fn($q) => $q->where('id', '!=', $activeTrip->id))
            ->orderByDesc('created_at')
            ->with('client')
            ->get();

        $todayStart = now()->startOfDay();
        $completedRidesCount = \App\Models\Trip::query()
            ->where('driver_id', $driverId)
            ->where('status', 'completed')
            ->where('updated_at', '>=', $todayStart)
            ->count();

        $totalGains = (float) \App\Models\Trip::query()
            ->where('driver_id', $driverId)
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->where('updated_at', '>=', $todayStart)
            ->sum('price');

        $isApproved = (bool) auth()->user()->is_approved;

        return view('driver.dashboard', compact(
            'activeTrip',
            'activeRental',
            'availableTrips',
            'completedRidesCount',
            'totalGains',
            'isApproved'
        ));
    })->name('driver.dashboard');

    Route::post('/trips/{trip}/accept', [\App\Http\Controllers\TripController::class, 'accept'])->name('trips.accept');
    Route::post('/trips/{trip}/start', [\App\Http\Controllers\TripController::class, 'start'])->name('trips.start');
    Route::post('/trips/{trip}/complete', [\App\Http\Controllers\TripController::class, 'complete'])->name('trips.complete');
    Route::post('/trips/{trip}/mark-paid', [\App\Http\Controllers\TripController::class, 'markPaid'])->name('trips.mark-paid');
});

// Shared Trip Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/trips/{trip}/cancel', [\App\Http\Controllers\TripController::class, 'cancel'])->name('trips.cancel');
    Route::post('/trips/{trip}/rate', [\App\Http\Controllers\TripController::class, 'rate'])->name('trips.rate');
    Route::post('/trips/{trip}/confirm-payment', [\App\Http\Controllers\TripController::class, 'confirmPayment'])->name('trips.confirm-payment');
    Route::post('/trips/{trip}/pay', [\App\Http\Controllers\PaymentController::class, 'process'])->name('trips.pay');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{user}/toggle', [\App\Http\Controllers\AdminController::class, 'toggleActive'])->name('admin.users.toggle');
    Route::post('/users/{user}/approve', [\App\Http\Controllers\AdminController::class, 'approveDriver'])->name('admin.users.approve');
    Route::get('/trips', [\App\Http\Controllers\AdminController::class, 'trips'])->name('admin.trips');
    Route::post('/trips/{trip}/cancel', [\App\Http\Controllers\AdminController::class, 'cancelTrip'])->name('admin.trips.cancel');
    Route::post('/trips/{trip}/assign', [\App\Http\Controllers\TripController::class, 'assign'])->name('trips.assign');

    Route::get('/rentals', [\App\Http\Controllers\AdminController::class, 'rentals'])->name('admin.rentals');
    Route::get('/rentals/{rental}/edit', [\App\Http\Controllers\AdminController::class, 'editRental'])->name('admin.rentals.edit');
    Route::post('/rentals/{rental}/update-status', [\App\Http\Controllers\AdminController::class, 'updateRentalStatus'])->name('admin.rentals.update-status');
    Route::post('/rentals/{rental}/confirm', [\App\Http\Controllers\AdminController::class, 'confirmRental'])->name('admin.rentals.confirm');
    Route::post('/rentals/{rental}/reject', [\App\Http\Controllers\AdminController::class, 'rejectRental'])->name('admin.rentals.reject');
    Route::get('/logs', [\App\Http\Controllers\AdminController::class, 'logs'])->name('admin.logs');
    
    // Invoices restricted to Admin
    Route::get('/trips/{trip}/invoice', [\App\Http\Controllers\InvoiceController::class, 'downloadTripInvoice'])->name('trips.invoice');
    Route::get('/rentals/{rental}/invoice', [\App\Http\Controllers\InvoiceController::class, 'downloadRentalInvoice'])->name('rentals.invoice');
});

// Chatbot Route
Route::post('/chatbot/message', [\App\Http\Controllers\ChatbotController::class, 'message'])->name('chatbot.message');

// ========== RENTAL ROUTES (Location de véhicules) ==========
Route::middleware(['auth'])->group(function () {
    Route::post('/rentals', [\App\Http\Controllers\RentalController::class, 'store'])->name('rentals.store');
    Route::get('/my-rentals', [\App\Http\Controllers\RentalController::class, 'myRentals'])->name('my.rentals');
    Route::get('/profile', [App\Http\Controllers\AuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\AuthController::class, 'updateProfile'])->name('profile.update');
});

Route::middleware(['auth', 'role:client'])
    ->prefix('client')
    ->group(function () {
        Route::get('/trips/{trip}/track', [TripController::class, 'track'])
            ->name('client.trips.track');
    });

// Contact Form Route
Route::post('/contact/send', [\App\Http\Controllers\ContactController::class, 'send'])->name('contact.send');