<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientAppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::where('patient_id', Auth::id())->with('doctor')->latest()->paginate(10);
        return view('patient.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $doctors = User::where('role', 'doctor')->get();
        return view('patient.appointments.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after:now',
            'reason' => 'required|string|max:1000',
        ]);

        Appointment::create([
            'patient_id' => Auth::id(),
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'reason' => $request->reason,
        ]);

        return redirect()->route('patient.appointments.index')->with('success', 'Appointment booked successfully!');
    }
}