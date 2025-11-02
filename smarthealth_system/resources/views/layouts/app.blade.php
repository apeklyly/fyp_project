<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script>
        (function() {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
                // For our app, we put it on the body
                document.body.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
                document.body.classList.remove('dark');
            }
        })();
    </script>
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
                <a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">My Profile</a>

                {{-- Patient Navigation --}}
                @if(Auth::user()->role == 'patient')
                    <a href="{{ route('patient.dashboard') }}" class="{{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('patient.checkup.create') }}" class="{{ request()->routeIs('patient.checkup.create') ? 'active' : '' }}">New Medical Checkup</a>
                    <a href="{{ route('patient.records') }}" class="{{ request()->routeIs('patient.records') ? 'active' : '' }}">My Records</a>
                    <a href="{{ route('patient.appointments.index') }}" class="{{ request()->routeIs('patient.appointments.*') ? 'active' : '' }}">My Appointments</a>
                    
                    <a href="{{ route('patient.messages.index') }}" class="{{ Request::is('patient/messages*') ? 'active' : '' }}" style="position: relative;">
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
                    
                    <a href="{{ route('doctor.messages.index') }}" class="{{ Request::is('doctor/messages*') ? 'active' : '' }}" style="position: relative;">
                        Patient Messages
                        @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                            <span class="notification-badge">{{ $unreadMessagesCount }}</span>
                        @endif
                    </a>
                @endif
                
                
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

    <div class="theme-toggle-wrapper">
        <label class="theme-toggle" for="theme-toggle-checkbox">
            <span class="theme-toggle-icon">☀️</span>
            <div class="theme-toggle-switch">
                <input type="checkbox" id="theme-toggle-checkbox">
                <span class="theme-toggle-slider"></span>
            </div>
            <span class="theme-toggle-icon">🌙</span>
        </label>
    </div>
    </div>
        </aside>

        <main class="content">
            {{ $slot }}
        </main>
    </div>
</body>
</html>