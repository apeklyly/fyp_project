<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;


class MessageController extends Controller
{
    // ===================================
    // PATIENT-SIDE METHODS
    // ===================================

    public function patientIndex()
    {
        // Show the patient a list of all doctors to message
        $doctors = User::where('role', 'doctor')->get();
        return view('patient.messages.index', compact('doctors'));
    }

    public function patientShow(User $doctor)
    {
        // Show the chat thread with a specific doctor
        $user = Auth::user();
        $messages = $this->getChatMessages($user, $doctor);

        // Mark messages from this doctor as read
        Message::where('sender_id', $doctor->id)
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('patient.messages.show', compact('doctor', 'messages'));
    }

    public function patientStore(Request $request, User $doctor)
    {
        // Patient sends a message to a doctor
        $this->storeMessage($request, $doctor);
        return back();
    }

    // ===================================
    // DOCTOR-SIDE METHODS
    // ===================================

    public function doctorIndex()
    {
        // Show the doctor a list of patients they have conversations with
        $doctor = Auth::user();
        
        // Get IDs of patients who sent messages to the doctor
        $patientSenders = Message::where('receiver_id', $doctor->id)
                                ->distinct()
                                ->pluck('sender_id');
        
        // Get IDs of patients the doctor has sent messages to
        $patientReceivers = Message::where('sender_id', $doctor->id)
                                ->distinct()
                                ->pluck('receiver_id');

        // Combine the lists, remove duplicates, and get the User models
        $patientIds = $patientSenders->merge($patientReceivers)->unique();
        $patients = User::whereIn('id', $patientIds)->where('role', 'patient')->get();

        return view('doctor.messages.index', compact('patients'));
    }

    public function doctorShow(User $patient)
    {
        // Show the chat thread with a specific patient
        $user = Auth::user();
        $messages = $this->getChatMessages($user, $patient);

        // Mark messages from this patient as read
        Message::where('sender_id', $patient->id)
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('doctor.messages.show', compact('patient', 'messages'));
    }

    public function doctorStore(Request $request, User $patient)
    {
        // Doctor sends a message to a patient
        $this->storeMessage($request, $patient);
        return back();
    }

    // ===================================
    // SHARED HELPER METHODS
    // ===================================

    private function getChatMessages(User $user1, User $user2)
    {
        // Get all messages between two users
        return Message::where(function ($query) use ($user1, $user2) {
            $query->where('sender_id', $user1->id)
                  ->where('receiver_id', $user2->id);
        })->orWhere(function ($query) use ($user1, $user2) {
            $query->where('sender_id', $user2->id)
                  ->where('receiver_id', $user1->id);
        })
        ->orderBy('created_at', 'asc')
        ->get();
    }

    private function storeMessage(Request $request, User $receiver)
    {
        // Validate and store the message
        $request->validate([
            'message' => 'nullable|string|required_without:attachment',
            'attachment' => 'nullable|file|mimes:jpg,png,jpeg,gif,pdf|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('message_attachments', 'public');
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver->id,
            'message' => $request->message,
            'file_path' => $filePath,
        ]);

       
    }
}