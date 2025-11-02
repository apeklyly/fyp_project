<x-app-layout>
    <div class="content-header">
        <h1>Patient Monitoring</h1>
        <p>Search and view your patients' latest health submissions.</p>
    </div>

    <div class="card">
        <form method="GET" action="{{ route('doctor.monitoring') }}" class="filter-form">
            <div class="filter-group">
                <input type="text" name="search" class="form-control" placeholder="Search by patient name..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Last Checkup</th>
                    <th>Heart Rate</th>
                    <th>Blood Pressure</th>
                    <th>Cholesterol</th>
                    <th>Blood Sugar</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                    @php
                        $latestRecord = $patient->healthRecords()->latest()->first();
                        $status = 'default';
                        $statusText = '';

                        if ($latestRecord) {
                            // Use our new model method to get the status
                            $status = $latestRecord->getVitalsStatus();

                            if ($status === 'danger') {
                                $statusText = 'HIGH CONCERN';
                            } elseif ($status === 'intermediate') {
                                $statusText = 'MONITORING ADVISED';
                            }
                        }
                    @endphp
                    {{-- Add a class to the table row based on the status --}}
                    <tr class="status-row-{{ $status }}">
                        <td>
                            {{ $patient->full_name }}
                            {{-- Display the status text if it exists --}}
                            @if($statusText)
                                <span class="status-text-{{$status}}">{{ $statusText }}</span>
                            @endif
                        </td>
                        <td>{{ $latestRecord ? $latestRecord->created_at->diffForHumans() : 'No data' }}</td>
                        <td>{{ $latestRecord?->heart_rate ?? 'N/A' }} bpm</td>
                        <td>{{ $latestRecord ? $latestRecord->systolic_pressure . ' / ' . $latestRecord->diastolic_pressure : 'N/A' }} mmHg</td>
                        <td>{{ $latestRecord?->cholesterol ?? 'N/A' }} mg/dL</td>
                        <td>{{ $latestRecord ? $latestRecord->blood_sugar_value . ' ' . $latestRecord->blood_sugar_unit : 'N/A' }}</td>
                        <td class="action-cell">
    <a href="{{ route('doctor.patient.show', $patient->id) }}" class="btn btn-secondary">View Details</a>
    <a href="{{ route('doctor.messages.show', $patient->id) }}" class="btn btn-primary">Message</a>
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem;">
                            No patients found matching your criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 2rem;">
            {{ $patients->appends(request()->query())->links('vendor.pagination.simple-custom') }}
        </div>
    </div>
</x-app-layout>