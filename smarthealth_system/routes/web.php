<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
// routes/web.php
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
// In routes/web.php
use App\Http\Controllers\PatientAppointmentController; // <-- Add at top
use App\Http\Controllers\DoctorAppointmentController;  // <-- Add at top

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Route for redirecting after login based on role
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'doctor') {
            return redirect()->route('doctor.dashboard');
        }
        return redirect()->route('patient.dashboard');
    })->name('dashboard');

});
// Patient Routes
Route::middleware(['role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
    Route::get('/checkup', [PatientController::class, 'createCheckup'])->name('checkup.create');
    Route::post('/checkup', [PatientController::class, 'storeCheckup'])->name('checkup.store');
    Route::get('/records', [PatientController::class, 'records'])->name('records');
    Route::get('/records/{record}', [PatientController::class, 'showRecord'])->name('record.show');
    Route::get('/messages', [PatientController::class, 'messages'])->name('messages');
    // Inside Patient Routes group
    Route::get('/appointments', [PatientAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [PatientAppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [PatientAppointmentController::class, 'store'])->name('appointments.store');
});

// routes/web.php (inside the middleware group)
Route::middleware(['role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
    Route::get('/monitoring', [DoctorController::class, 'monitoring'])->name('monitoring');
    Route::get('/patient/{user}', [DoctorController::class, 'showPatient'])->name('patient.show');
    Route::post('/patient/{user}/message', [DoctorController::class, 'sendMessage'])->name('patient.sendMessage');
    Route::get('/appointments', [DoctorAppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/appointments/{appointment}', [DoctorAppointmentController::class, 'update'])->name('appointments.update');
    Route::get('/patient-record/{record}', [DoctorController::class, 'showPatientRecord'])->name('record.show');
    Route::get('/patient/{user}/message', [DoctorController::class, 'createMessage'])->name('patient.message.create');
});




require __DIR__ . '/auth.php';
