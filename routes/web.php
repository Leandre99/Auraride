<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/contact', function () { return view('contact'); })->name('contact');
Route::get('/tarifs', function () { return view('prices'); })->name('prices');

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
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Client Routes
Route::middleware(['auth', 'role:client'])->prefix('client')->group(function () {
    Route::get('/dashboard', function () {
        $trackingTrip = \App\Models\Trip::query()
            ->where('client_id', auth()->id())
            ->where('status', '!=', 'cancelled')
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

        return view('client.dashboard', compact('trackingTrip'));
    })->name('client.dashboard');

    Route::post('/trips/estimate', [\App\Http\Controllers\TripController::class, 'estimate'])->name('trips.estimate');
    Route::post('/trips', [\App\Http\Controllers\TripController::class, 'store'])->name('trips.store');
});

// Driver Routes placeholder
Route::middleware(['auth', 'role:driver'])->prefix('driver')->group(function () {
    Route::get('/dashboard', function () {
        $driverId = auth()->id();

        $activeTrip = \App\Models\Trip::query()
            ->where('driver_id', $driverId)
            ->where(function ($q) {
                $q->whereIn('status', ['assigned', 'accepted', 'in_progress'])
                    ->orWhere(function ($q2) {
                        $q2->where('status', 'completed')
                            ->where(function ($q3) {
                                $q3->whereNull('payment_status')
                                    ->orWhere('payment_status', '!=', 'paid');
                            });
                    });
            })
            ->orderByDesc('updated_at')
            ->with(['client', 'vehicle.vehicleType'])
            ->first();

        $availableTrips = \App\Models\Trip::query()
            ->where('driver_id', $driverId)
            ->where('status', 'assigned')
            ->when($activeTrip, fn ($q) => $q->where('id', '!=', $activeTrip->id))
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
            'availableTrips',
            'completedRidesCount',
            'totalGains',
            'isApproved'
        ));
    })->name('driver.dashboard');

    Route::post('/trips/{trip}/accept', [\App\Http\Controllers\TripController::class, 'accept'])->name('trips.accept');
    Route::post('/trips/{trip}/start', [\App\Http\Controllers\TripController::class, 'start'])->name('trips.start');
    Route::post('/trips/{trip}/complete', [\App\Http\Controllers\TripController::class, 'complete'])->name('trips.complete');
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
    Route::post('/rentals', [\App\Http\Controllers\RentalController::class, 'store'])->name('rentals.store');
});
