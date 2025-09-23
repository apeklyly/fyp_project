<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthRecord;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Http;

class PatientController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $latestRecord = $user->healthRecords()->latest()->first();
        $unreadMessages = $user->receivedMessages()->whereNull('read_at')->count();

        return view('patient.dashboard', compact('latestRecord', 'unreadMessages'));
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
            'blood_sugar_value' => 'required|numeric|min:1|max:500',
            'blood_sugar_unit' => 'required|string|in:mg/dL,mmol/L',
            'symptoms' => 'required|array',
            'notes' => 'nullable|string',
        ]);

        $recommendation = $this->generateAiRecommendation(
            $request->heart_rate,
            $request->systolic_pressure,
            $request->diastolic_pressure,
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
            'blood_sugar_value' => $request->blood_sugar_value,
            'blood_sugar_unit' => $request->blood_sugar_unit,
            'symptoms' => json_encode($request->symptoms),
            'notes' => $request->notes,
            'recommendation' => $recommendation,
        ]);

        return redirect()->route('patient.record.show', $record->id)->with('success', 'Your health data has been successfully submitted and is now available for your doctor to review.');
    }

    private function generateAiRecommendation($heartRate, $systolic, $diastolic, $bloodSugarVal, $bloodSugarUnit, $symptoms, $notes)
    {
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return json_encode(['error' => 'AI service is not configured. The API key is missing.']);
        }

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey;

        $symptomsString = implode(', ', $symptoms);

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
          - `url`: A real, working URL from YouTube or Reddit.

        ### CURRENT USER DATA TO ANALYZE
        - Heart Rate: $heartRate bpm
        - Blood Pressure: $systolic / $diastolic mmHg
        - Blood Sugar: $bloodSugarVal $bloodSugarUnit
        - Reported Symptoms: $symptomsString
        - User Notes: $notes

        Now, generate the JSON response.
        ";

        try {
            $response = Http::post($url, [
                'contents' => [['parts' => [['text' => $prompt]]]],
                'generationConfig' => ['responseMimeType' => 'application/json']
            ]);

            if ($response->successful() && !empty($response->json('candidates'))) {
                $aiContent = $response->json('candidates.0.content.parts.0.text');
                $disclaimer = "\n\n**Disclaimer:** This is an AI-generated suggestion based on the data provided and is for informational purposes only. It is not a medical diagnosis. Please consult a qualified healthcare professional for any health concerns.";
                // We are now saving the AI's JSON output directly
                return $aiContent;
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
}