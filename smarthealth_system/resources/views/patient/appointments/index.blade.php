<x-app-layout>
    <div class="content-header"><h1>My Appointments</h1></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card">
        <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary" style="margin-bottom: 1rem;">Book New Appointment</a>
        <table class="table">
            <thead><tr><th>Doctor</th><th>Date & Time</th><th>Reason</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($appointments as $appointment)
                    <tr>
                        <td>Dr. {{ $appointment->doctor->full_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y, h:i A') }}</td>
                        <td>{{ Str::limit($appointment->reason, 40) }}</td>
                        <td><span class="status-{{ strtolower($appointment->status) }}">{{ $appointment->status }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">No appointments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>