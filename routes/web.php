<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Client Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        // Only allow clients
        if(auth()->user()->role !== 'client') {
            return redirect('/' . auth()->user()->role . '/dashboard');
        }
        return view('client.dashboard');
    })->name('dashboard');
});

// Driver Routes placeholder
Route::middleware(['auth', 'role:driver'])->prefix('driver')->group(function () {
    Route::get('/dashboard', function () {
        return "Driver Dashboard Integration Pending...";
    });
});

// Admin Routes placeholder
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return "Admin Dashboard Integration Pending...";
    });
});
