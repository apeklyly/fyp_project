<x-app-layout>
    <div class="content-header">
        <h1>Patient Monitoring</h1>
        <p>An overview of your patients' latest health submissions.</p>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Last Checkup</th>
                    <th>Heart Rate</th>
                    <th>Blood Pressure</th>
                    <th>Blood Sugar</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    @forelse($patients as $patient)
        @php
            $latestRecord = $patient->healthRecords->sortByDesc('created_at')->first();
            $isCritical = false; // Default to not critical

            if ($latestRecord) {
                // Decode the AI's JSON recommendation
                $aiData = json_decode($latestRecord->recommendation, true);
                $aiStatus = $aiData['overall_status'] ?? null;

                // 1. Check if AI status is critical
                $aiIsCritical = in_array($aiStatus, ['High Concern', 'Action Required']);

                // 2. Check if vitals are critical based on hardcoded rules
                $vitalsAreCritical = (
                    $latestRecord->cholesterol > 240 || 
                    $latestRecord->blood_sugar_value > 200 ||
                    $latestRecord->systolic_pressure > 180 ||
                    $latestRecord->heart_rate > 120 ||
                    in_array('Chest Pain', json_decode($latestRecord->symptoms, true) ?? [])
                );
                
                // A patient is critical if EITHER the AI says so OR their vitals are in the danger zone
                $isCritical = $aiIsCritical || $vitalsAreCritical;
            }
        @endphp
        <tr style="{{ $isCritical ? 'background-color: #FEF2F2;' : '' }}">
            <td>
                {{ $patient->full_name }}
                @if($isCritical)
                    <span style="color: #DC2626; font-weight: bold; font-size: 0.8rem; display: block;">CRITICAL ALERT</span>
                @endif
            </td>
            <td>{{ $latestRecord ? $latestRecord->created_at->diffForHumans() : 'No data' }}</td>
            <td>{{ $latestRecord?->heart_rate ?? 'N/A' }} bpm</td>
            <td>{{ $latestRecord ? $latestRecord->systolic_pressure . ' / ' . $latestRecord->diastolic_pressure : 'N/A' }} mmHg</td>
            <td>{{ $latestRecord?->cholesterol ?? 'N/A' }} mg/dL</td>
            <td>{{ $latestRecord ? $latestRecord->blood_sugar_value . ' ' . $latestRecord->blood_sugar_unit : 'N/A' }}</td>
            <td>
                <a href="{{ route('doctor.patient.show', $patient->id) }}" class="btn btn-secondary">View Details</a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" style="text-align: center; padding: 2rem;">
                There are no patients registered in the system yet.
            </td>
        </tr>
    @endforelse
</tbody>
        </table>

        <div style="margin-top: 2rem;">
            {{ $patients->links('vendor.pagination.simple-custom') }}
        </div>
    </div>
</x-app-layout>