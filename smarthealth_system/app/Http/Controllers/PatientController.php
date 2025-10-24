<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthRecord;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Http;
use App\Models\Appointment;

class PatientController extends Controller
{
   public function dashboard()
{
    $user = Auth::user();

    // Get the single latest record for the stat cards
    $latestRecord = $user->healthRecords()->latest()->first();

    // Get the count of unread messages
    $unreadMessagesCount = $user->receivedMessages()->whereNull('read_at')->count();

    // Find the next upcoming 'Approved' appointment
    $upcomingAppointment = $user->appointments()
                                ->where('status', 'Approved')
                                ->where('appointment_date', '>=', now())
                                ->orderBy('appointment_date', 'asc')
                                ->first();

    // Get the last 30 days of records for ALL THREE charts
    $healthRecordsForChart = $user->healthRecords()
                                  ->where('created_at', '>=', now()->subDays(30))
                                  ->orderBy('created_at', 'asc')
                                  ->select('created_at', 'systolic_pressure', 'diastolic_pressure', 'heart_rate', 'blood_sugar_value', 'cholesterol')
                                  ->get();

    return view('patient.dashboard', compact(
        'latestRecord',
        'unreadMessagesCount',
        'upcomingAppointment',
        'healthRecordsForChart'
    ));
}

    public function createCheckup()
    {
        return view('patient.checkup');
    }

    public function storeCheckup(Request $request)
    {
        $request->validate([
            'heart_rate' => 'required|integer|min:40|max:200',
            'systolic_pressure' => 'required|integer|min:70|max:250',
            'diastolic_pressure' => 'required|integer|min:40|max:150',
             'cholesterol' => 'nullable|integer|min:100|max:500',
            'blood_sugar_value' => 'required|numeric|min:1|max:500',
            'blood_sugar_unit' => 'required|string|in:mg/dL,mmol/L',
            'symptoms' => 'required|array',
            'notes' => 'nullable|string',
        ]);

        $recommendation = $this->generateAiRecommendation(
            $request->heart_rate,
            $request->systolic_pressure,
            $request->diastolic_pressure,
             $request->cholesterol, 
            $request->blood_sugar_value,
            $request->blood_sugar_unit,
            $request->symptoms,
            $request->notes
        );

        $record = HealthRecord::create([
            'user_id' => Auth::id(),
            'heart_rate' => $request->heart_rate,
            'systolic_pressure' => $request->systolic_pressure,
            'diastolic_pressure' => $request->diastolic_pressure,
            'cholesterol' => $request->cholesterol, 
            'blood_sugar_value' => $request->blood_sugar_value,
            'blood_sugar_unit' => $request->blood_sugar_unit,
            'symptoms' => json_encode($request->symptoms),
            'notes' => $request->notes,
            'recommendation' => $recommendation,
        ]);

        return redirect()->route('patient.record.show', $record->id)->with('success', 'Your health data has been successfully submitted and is now available for your doctor to review.');
    }

    private function generateAiRecommendation($heartRate, $systolic, $diastolic, $cholesterol, $bloodSugarVal, $bloodSugarUnit, $symptoms, $notes)
{
    $apiKey = config('services.gemini.key');
    
    if (!$apiKey) {
        return json_encode(['error' => 'AI service is not configured. Please check your services config file.']);
    }

    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-lite-latest:generateContent?key=' . $apiKey;

    $symptomsString = implode(', ', $symptoms);

    // CHANGED: The prompt now asks for a 'search_query' instead of a 'url'
    $prompt = "
    ### ROLE AND GOAL
    You are a health data analysis AI. Your task is to process user-submitted health data and return a structured JSON object. DO NOT output any text outside of the JSON object.

    ### JSON STRUCTURE AND RULES
    Your response MUST be a valid JSON object with the following structure:
    - `overall_status`: A single summary word. Choose one: 'Good', 'Monitoring Advised', 'High Concern'.
    - `metrics`: An array of objects, one for each health metric.
      - `name`: The name of the metric (e.g., 'Heart Rate').
      - `value`: The user's reading (e.g., '$heartRate bpm').
      - `status`: A single word describing the reading. Choose one: 'Normal', 'Elevated', 'High', 'Low'.
    - `key_advice`: An array of short, simple, actionable advice strings. Each string should be under 15 words.
    - `resource_links`: An array of objects for helpful resources.
      - `title`: A short title for the link.
      - `search_query`: A short, relevant Google search term (e.g., 'DASH diet recipes for high blood pressure').

    ### CURRENT USER DATA TO ANALYZE
    - Heart Rate: $heartRate bpm
    - Blood Pressure: $systolic / $diastolic mmHg
    - Total Cholesterol: $cholesterol mg/dL
    - Blood Sugar: $bloodSugarVal $bloodSugarUnit
    - Reported Symptoms: $symptomsString
    - User Notes: $notes

    Now, generate the JSON response.
    ";

    try {
        $response = Http::post($url, [
            'contents' => [['parts' => [['text' => $prompt]]]],
        ]);

        if ($response->successful() && !empty($response->json('candidates'))) {
            $aiContent = $response->json('candidates.0.content.parts.0.text');
            
            // NEW: Process the AI response to build the Google search links
            $data = json_decode($aiContent, true);

            if (isset($data['resource_links'])) {
                foreach ($data['resource_links'] as &$link) { // Use a reference to modify the array
                    if (isset($link['search_query'])) {
                        $link['url'] = 'https://www.google.com/search?q=' . urlencode($link['search_query']);
                    }
                }
            }
            
            // Return the modified data as a JSON string
            return json_encode($data);

        } else {
            return json_encode(['error' => 'The AI API returned an invalid response: ' . $response->body()]);
        }

    } catch (Exception $e) {
        return json_encode(['error' => 'Could not connect to the AI service: ' . $e->getMessage()]);
    }
}
    public function records()
    {
        $records = Auth::user()->healthRecords()->latest()->paginate(10);
        return view('patient.records', compact('records'));
    }

    public function showRecord(HealthRecord $record)
    {
        if ($record->user_id !== Auth::id()) {
            abort(403);
        }
        return view('patient.record-show', compact('record'));
    }

    public function messages()
    {
        $user = Auth::user();

        // Mark all unread messages as read
        $user->receivedMessages()->whereNull('read_at')->update(['read_at' => now()]);

        // Then, fetch all messages to display them
        $messages = $user->receivedMessages()->latest()->get();

        return view('patient.messages', compact('messages'));
    }

public function showPrintableView(HealthRecord $record)
{
    // Security check: ensure the logged-in user owns this record
    if ($record->user_id !== Auth::id()) {
        abort(403);
    }

    // Return the new print-specific view
    return view('patient.record-print', compact('record'));
}
}