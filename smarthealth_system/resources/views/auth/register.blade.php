<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register - {{ config('app.name', 'SmartHealth') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: #111827;
            display: grid;
            place-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 2rem 0;
        }
        .card {
            background-color: #1F2937; /* Dark card background */
            color: #f9fafb; /* Light text */
            padding: 2.5rem;
            border-radius: 0.75rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 1.75rem;
            font-weight: 600;
            text-align: center;
            margin: 0 0 2rem 0;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }
        .form-input {
            display: block;
            width: 100%;
            box-sizing: border-box; /* Important for padding */
            background-color: #374151; /* Darker input background */
            border: 1px solid #4B5563; /* Subtle border */
            color: #f9fafb;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.2s;
        }
        .form-input:focus {
            outline: 2px solid #2563EB;
            border-color: transparent;
        }
        /* Style for date input placeholder */
        .form-input[type="date"] {
            color-scheme: dark;
        }
        .form-button {
            display: block;
            width: 100%;
            background-color: #10B981; /* Green button */
            color: #ffffff;
            font-weight: 600;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.2s;
        }
        .form-button:hover {
            background-color: #059669;
        }
        .form-link {
            color: #10B981; /* Green link */
            text-decoration: none;
            font-size: 0.875rem;
        }
        .form-link:hover {
            text-decoration: underline;
        }
        .form-error {
            color: #EF4444; /* Red error text */
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
        }
    </style>
</head>
<body>

    <div class="card">
        <h1>Register Your Account</h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="full_name" class="form-label">Full Name</label>
                <input id="full_name" class="form-input" type="text" name="full_name" value="{{ old('full_name') }}" required autofocus autocomplete="name" />
                @error('full_name') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input id="username" class="form-input" type="text" name="username" value="{{ old('username') }}" required autocomplete="username" />
                @error('username') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" />
                @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            
            <div class="form-group">
                <label for="birth_date" class="form-label">Birth Date</label>
                <input id="birth_date" class="form-input" type="date" name="birth_date" value="{{ old('birth_date') }}" required />
                @error('birth_date') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            
            <div class="form-group">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input id="phone_number" class="form-input" type="text" name="phone_number" value="{{ old('phone_number') }}" required />
                @error('phone_number') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="role" class="form-label">Register as a...</label>
                <select name="role" id="role" class="form-input">
                    <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                    <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                </select>
                @error('role') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input id="password" class="form-input"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
                @error('password') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input id="password_confirmation" class="form-input"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                @error('password_confirmation') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            
            <div class="form-group">
                <button type="submit" class="form-button">
                    Register
                </button>
            </div>

            <div class="form-footer">
                <a class="form-link" href="{{ route('login') }}">
                    Already registered? Log in
                </a>
            </div>
        </form>
    </div>

</body>
</html>