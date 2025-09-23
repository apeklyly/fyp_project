<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    /**
     * Display the user's profile in a read-only view.
     */
    public function show()
    {
        return view('profile.show', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // This logic handles both forms (details and password)
        if ($request->has('username')) {
            // Handle Profile Information Update
            $request->validate([
                'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
                'full_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'birth_date' => ['required', 'date'],
                'phone_number' => ['required', 'string', 'max:20'],
            ]);

            $user->username = $request->username;
            $user->full_name = strtoupper($request->full_name);
            $user->email = $request->email;
            $user->birth_date = $request->birth_date;
            $user->phone_number = $request->phone_number;

        } elseif ($request->filled('password')) {
            // Handle Password Update
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Redirect back to the VIEW page with a success message
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }
}