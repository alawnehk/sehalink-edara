<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Main Dashboard - Handled by DashboardController
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Module Placeholders (Returning dashboard view as requested)
    Route::get('/patients', function () {
        return redirect()->route('dashboard'); 
    })->name('patients.index');

    Route::get('/appointments', function () {
        return redirect()->route('dashboard');
    })->name('appointments.index');

    // Breeze Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
