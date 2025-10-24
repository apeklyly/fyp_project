<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientAppointmentController;
use App\Http\Controllers\DoctorAppointmentController;

// Welcome Page
Route::get('/', function () {
    return view('welcome');
});

// Main Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Redirects users to their specific dashboard after login
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'doctor') {
            return redirect()->route('doctor.dashboard');
        }
        return redirect()->route('patient.dashboard');
    })->name('dashboard');

    // Profile Routes (for both roles)
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

});

// Patient-Specific Routes
Route::middleware(['auth', 'verified', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
    Route::get('/checkup', [PatientController::class, 'createCheckup'])->name('checkup.create');
    Route::post('/checkup', [PatientController::class, 'storeCheckup'])->name('checkup.store');
    Route::get('/records', [PatientController::class, 'records'])->name('records');
    Route::get('/records/{record}', [PatientController::class, 'showRecord'])->name('record.show');
    Route::get('/messages', [PatientController::class, 'messages'])->name('messages');
    
    // Appointments
    Route::get('/appointments', [PatientAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [PatientAppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [PatientAppointmentController::class, 'store'])->name('appointments.store');

    // CORRECTED Print Route
    Route::get('/records/{record}/print', [PatientController::class, 'showPrintableView'])->name('record.print');
});

// Doctor-Specific Routes
Route::middleware(['auth', 'verified', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
    Route::get('/monitoring', [DoctorController::class, 'monitoring'])->name('monitoring');
    Route::get('/patient/{user}', [DoctorController::class, 'showPatient'])->name('patient.show');
    Route::get('/patient-record/{record}', [DoctorController::class, 'showPatientRecord'])->name('record.show');

    // Messaging
    Route::get('/patient/{user}/message', [DoctorController::class, 'createMessage'])->name('patient.message.create');
    Route::post('/patient/{user}/message', [DoctorController::class, 'sendMessage'])->name('patient.sendMessage');
    
    // Appointments
    Route::get('/appointments', [DoctorAppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/appointments/{appointment}', [DoctorAppointmentController::class, 'update'])->name('appointments.update');
});

require __DIR__ . '/auth.php';