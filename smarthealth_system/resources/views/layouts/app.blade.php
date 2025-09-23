<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SmartHealth') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="main-layout">
        <aside class="sidebar">
            <div class="sidebar-header">
                SmartHealth
            </div>

            <nav class="sidebar-nav">
                {{-- Universal Links --}}
               

                {{-- Patient Navigation --}}
                @if(Auth::user()->role == 'patient')
                    <a href="{{ route('patient.dashboard') }}" class="{{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">Dashboard</a>
                     <a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">My Profile</a>
                    <a href="{{ route('patient.checkup.create') }}" class="{{ request()->routeIs('patient.checkup.create') ? 'active' : '' }}">New Medical Checkup</a>
                    <a href="{{ route('patient.records') }}" class="{{ request()->routeIs('patient.records') ? 'active' : '' }}">My Records</a>
                    <a href="{{ route('patient.appointments.index') }}" class="{{ request()->routeIs('patient.appointments.*') ? 'active' : '' }}">My Appointments</a>
                    <a href="{{ route('patient.messages') }}" class="{{ request()->routeIs('patient.messages') ? 'active' : '' }}" style="position: relative;">
                        Doctor Messages
                        @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                            <span class="notification-badge">{{ $unreadMessagesCount }}</span>
                        @endif
                    </a>

                {{-- Doctor Navigation --}}
                @elseif(Auth::user()->role == 'doctor')
                    <a href="{{ route('doctor.dashboard') }}" class="{{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('doctor.monitoring') }}" class="{{ request()->routeIs('doctor.monitoring') ? 'active' : '' }}">Patient Monitoring</a>
                    <a href="{{ route('doctor.appointments.index') }}" class="{{ request()->routeIs('doctor.appointments.*') ? 'active' : '' }}">Manage Appointments</a>
                @endif
                
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                             document.getElementById('logout-form-top').submit();">
                    Logout
                </a>
            </nav>

            <form id="logout-form-top" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>


            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="content">
            {{ $slot }}
        </main>
    </div>
</body>
</html>