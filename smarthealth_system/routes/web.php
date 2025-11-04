<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientAppointmentController;
use App\Http\Controllers\DoctorAppointmentController;
use App\Http\Controllers\MessageController;

// Welcome Page
Route::get('/', function () {
    return view('welcome');
});

// Main Authenticated Routes
Route::middleware(['auth'])->group(function () {
    
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
Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
    Route::get('/checkup', [PatientController::class, 'createCheckup'])->name('checkup.create');
    Route::post('/checkup', [PatientController::class, 'storeCheckup'])->name('checkup.store');
    Route::get('/records', [PatientController::class, 'records'])->name('records');
    Route::get('/records/{record}', [PatientController::class, 'showRecord'])->name('record.show');
    Route::get('/messages', [MessageController::class, 'patientIndex'])->name('messages.index');
    Route::get('/messages/{doctor}', [MessageController::class, 'patientShow'])->name('messages.show');
    Route::post('/messages/{doctor}', [MessageController::class, 'patientStore'])->name('messages.store');
    
    // Appointments
    Route::get('/appointments', [PatientAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [PatientAppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [PatientAppointmentController::class, 'store'])->name('appointments.store');

    // CORRECTED Print Route
    Route::get('/records/{record}/print', [PatientController::class, 'showPrintableView'])->name('record.print');
});

// Doctor-Specific Routes
Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
    Route::get('/monitoring', [DoctorController::class, 'monitoring'])->name('monitoring');
    Route::get('/patient/{user}', [DoctorController::class, 'showPatient'])->name('patient.show');
    Route::get('/patient-record/{record}', [DoctorController::class, 'showPatientRecord'])->name('record.show');
    Route::get('/guidelines', [DoctorController::class, 'editGuidelines'])->name('guidelines.edit');
    Route::post('/guidelines', [DoctorController::class, 'updateGuidelines'])->name('guidelines.update');

    // Messaging
   Route::get('/messages', [MessageController::class, 'doctorIndex'])->name('messages.index');
    Route::get('/messages/{patient}', [MessageController::class, 'doctorShow'])->name('messages.show');
    Route::post('/messages/{patient}', [MessageController::class, 'doctorStore'])->name('messages.store');
    
    // Appointments
    Route::get('/appointments', [DoctorAppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/appointments/{appointment}', [DoctorAppointmentController::class, 'update'])->name('appointments.update');
});

require __DIR__ . '/auth.php';