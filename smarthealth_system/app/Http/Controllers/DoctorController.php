<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\HealthRecord;
use App\Models\Appointment;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function dashboard()
    {
        $doctor = Auth::user();
        $patientCount = User::where('role', 'patient')->count();
        $pendingAppointmentsCount = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'Pending')
            ->count();

        // Get all patients and pre-load their single latest health record
        $allPatients = User::where('role', 'patient')->with([
            'healthRecords' => function ($query) {
                $query->latest()->limit(1);
            }
        ])->get();

        $criticalPatientsRecords = [];
        foreach ($allPatients as $patient) {
            $latestRecord = $patient->healthRecords->first(); // Get the single latest record

            if (!$latestRecord) {
                continue; // Skip patients who have no records
            }

            // This is the same critical alert logic from the Monitoring page
            $aiData = json_decode($latestRecord->recommendation, true);
            $aiStatus = $aiData['overall_status'] ?? null;
            $aiIsCritical = in_array($aiStatus, ['High Concern', 'Action Required']);

            $vitalsAreCritical = (
                $latestRecord->blood_sugar_value > 200 ||
                $latestRecord->systolic_pressure > 180 ||
                $latestRecord->heart_rate > 120 ||
                in_array('Chest Pain', json_decode($latestRecord->symptoms, true) ?? [])
            );

            if ($aiIsCritical || $vitalsAreCritical) {
                $criticalPatientsRecords[] = $latestRecord;
            }
        }

        $criticalRecords = collect($criticalPatientsRecords);

        return view('doctor.dashboard', compact(
            'patientCount',
            'pendingAppointmentsCount',
            'criticalRecords'
        ));
    }

    public function monitoring()
    {
        $patients = User::where('role', 'patient')->with('healthRecords')->paginate(15);
        return view('doctor.monitoring', compact('patients'));
    }

    public function showPatient(User $user)
    {
        // Ensure we are only fetching patients
        if ($user->role !== 'patient') {
            abort(404);
        }
        $records = $user->healthRecords()->latest()->paginate(10);
        return view('doctor.patient-show', compact('user', 'records'));
    }

    public function sendMessage(Request $request, User $user)
    {
        $request->validate(['message' => 'required|string']);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Message sent successfully!');
    }

    public function createMessage(User $user)
    {
        // Security check to ensure the user is a patient
        if ($user->role !== 'patient') {
            abort(404);
        }
        return view('doctor.messages.create', compact('user'));
    }

    public function showPatientRecord(HealthRecord $record)
    {
        // Security check to ensure the record belongs to a patient
        if ($record->user->role !== 'patient') {
            abort(404);
        }

        // The doctor can now view the patient's record page
        return view('patient.record-show', compact('record'));
    }


}