<x-app-layout>
    <div class="content-header"><h1>Manage Appointments</h1></div>
     @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card">
        <table class="table">
            <thead><tr><th>Patient</th><th>Date & Time</th><th>Reason</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
                @forelse($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->patient->full_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y, h:i A') }}</td>
                        <td>{{ Str::limit($appointment->reason, 30) }}</td>
                        <td><span class="status-{{ strtolower($appointment->status) }}">{{ $appointment->status }}</span></td>
                        <td>
                            <form method="POST" action="{{ route('doctor.appointments.update', $appointment) }}">
                                @csrf @method('patch')
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="Pending" @if($appointment->status == 'Pending') selected @endif>Pending</option>
                                    <option value="Approved" @if($appointment->status == 'Approved') selected @endif>Approve</option>
                                    <option value="Completed" @if($appointment->status == 'Completed') selected @endif>Complete</option>
                                    <option value="Cancelled" @if($appointment->status == 'Cancelled') selected @endif>Cancel</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">No appointments scheduled.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>