<x-app-layout>
    <div class="content-header">
        <h1>Doctor Dashboard</h1>
        <p>Welcome back, Dr. {{ Auth::user()->full_name }}.</p>
    </div>

    <div class="dashboard-grid">
        <div class="stat-card">
            <h4>Total Patients</h4>
            <p>{{ $patientCount }}</p>
        </div>
        <div class="stat-card">
            <h4>Pending Appointments</h4>
            <p>{{ $pendingAppointmentsCount }}</p>
        </div>
        <div class="stat-card critical">
            <h4>Critical Alerts (24h)</h4>
            <p>{{ $criticalRecords->count() }}</p>
        </div>
    </div>

    <div class="card">
        <h2>Recent Critical Alerts</h2>
        @if($criticalRecords->isEmpty())
            <div class="alert alert-success" style="color: #15803D; background-color: #DCFCE7;">
                All patients are currently stable. No critical alerts in the last 24 hours.
            </div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Alert Reason</th>
                        <th>Time Submitted</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($criticalRecords as $record)
                        <tr>
                            <td>{{ $record->user->full_name }}</td>
                            <td>
                                @php
                                    $aiData = json_decode($record->recommendation, true);
                                    $aiStatus = $aiData['overall_status'] ?? null;
                                @endphp
                                @if(in_array($aiStatus, ['High Concern', 'Action Required']))
                                    <span class="status-cancelled">AI: {{ $aiStatus }}</span>
                                @else
                                    <span class="status-cancelled">Abnormal Vitals</span>
                                @endif
                            </td>
                            <td>{{ $record->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('doctor.patient.show', $record->user->id) }}" class="btn btn-secondary btn-sm">View Patient</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>