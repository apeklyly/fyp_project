<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\HealthRecord;
use App\Models\Appointment;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Models\HealthGuideline;
use Illuminate\Support\Facades\Cache;

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

    public function monitoring(Request $request)
{
    // Start the query for users with the 'patient' role
    $query = User::where('role', 'patient');

    // If there is a search term in the URL, filter the results
    if ($request->filled('search')) {
        $query->where('full_name', 'LIKE', '%' . $request->search . '%');
    }

    // Get the paginated results and load the health records for each patient
    $patients = $query->with('healthRecords')->paginate(15);

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
    $request->validate([
        // Make text message optional if an attachment is present
        'message' => 'nullable|string|required_without:attachment',
        // Add validation for the file
        'attachment' => 'nullable|file|mimes:jpg,png,jpeg,gif,pdf|max:5120', // 5MB Max
    ]);

    $filePath = null;
    if ($request->hasFile('attachment')) {
        // Store the file in `storage/app/public/message_attachments`
        $filePath = $request->file('attachment')->store('message_attachments', 'public');
    }

    Message::create([
        'sender_id' => Auth::id(),
        'receiver_id' => $user->id,
        'message' => $request->message,
        'file_path' => $filePath, // 3. Save the path to the database
    ]);

    return redirect()->route('doctor.patient.show', $user->id)->with('success', 'Message sent successfully!');
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

    public function editGuidelines()
{
    // Get all guidelines, keyed by their 'metric' for easy access
    $guidelines = HealthGuideline::all()->keyBy('metric');
    return view('doctor.guidelines', compact('guidelines'));
}

public function updateGuidelines(Request $request)
{
    // Validate all the incoming data
    $validated = $request->validate([
        'hr_danger_low' => 'required|integer',
        'hr_normal_high' => 'required|integer',
        'bp_normal_systolic' => 'required|integer',
        'bp_normal_diastolic' => 'required|integer',
        'bp_elevated_systolic' => 'required|integer',
        'bp_danger_systolic' => 'required|integer',
        'bp_danger_diastolic' => 'required|integer',
        'sugar_danger_low' => 'required|integer',
        'sugar_normal_high' => 'required|integer',
        'sugar_danger_high' => 'required|integer',
        'cholesterol_normal' => 'required|integer',
        'cholesterol_borderline' => 'required|integer',
        'cholesterol_high' => 'required|integer',
    ]);

    // Loop over the validated data and update the database
    foreach ($validated as $metric => $value) {
        HealthGuideline::where('metric', $metric)->update(['value' => $value]);
    }
    Cache::forget('health_guidelines');

    return redirect()->route('doctor.guidelines.edit')->with('success', 'Health guidelines updated successfully!');
}

}