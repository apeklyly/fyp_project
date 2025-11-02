<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ config('app.name', 'SmartHealth') }}</title>

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
            justify-content: flex-end; /* Align link to the right */
            align-items: center;
            margin-top: 2rem;
        }
        /* For session status messages */
        .session-status {
            background-color: #059669;
            color: #ffffff;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>

    <div class="card">
        <h1>Login to Your Account</h1>

        @if (session('status'))
            <div class="session-status">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email or Username</label>
                <input id="email" class="form-input" type="text" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" />
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input id="password" class="form-input"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="form-group">
                <button type="submit" class="form-button">
                    Log In
                </button>
            </div>

            <div class="form-footer">
                <a class="form-link" href="{{ route('register') }}">
                    New user? Register
                </a>
            </div>
        </form>
    </div>

</body>
</html>