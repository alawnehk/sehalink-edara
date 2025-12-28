<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\EncounterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Patient Management
    Route::resource('patients', PatientController::class);

    // Appointment Management
    Route::resource('appointments', AppointmentController::class);

    // Clinics & Specialties
    Route::resource('clinics', ClinicController::class);
    Route::resource('specialties', SpecialtyController::class);

    // Doctor Management
    Route::resource('doctors', DoctorController::class)->except(['show']);
    Route::get('doctors/{doctor}/availability', [DoctorController::class, 'availability'])->name('doctors.availability');
    Route::post('doctors/{doctor}/availability', [DoctorController::class, 'availabilityStore'])->name('doctors.availability.store');
    Route::delete('doctors/{doctor}/availability/{availability}', [DoctorController::class, 'availabilityDestroy'])->name('doctors.availability.destroy');

    // Outpatient Encounters
    Route::resource('encounters', EncounterController::class);
    Route::post('encounters/{encounter}/close', [EncounterController::class, 'close'])->name('encounters.close');
    Route::get('encounters/create/from-appointment/{appointment}', [EncounterController::class, 'createFromAppointment'])->name('encounters.createFromAppointment');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';