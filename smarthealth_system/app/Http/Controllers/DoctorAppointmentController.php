<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DoctorAppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::where('doctor_id', Auth::id())->with('patient')->latest()->paginate(10);
        return view('doctor.appointments.index', compact('appointments'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        // Authorization check
        if ($appointment->doctor_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['status' => 'required|in:Approved,Cancelled,Completed']);

        // Store the new status
        $newStatus = $request->status;

     

        // Now, update the appointment status
        $appointment->update(['status' => $newStatus]);

        return back()->with('success', 'Appointment status updated!');
    }
}