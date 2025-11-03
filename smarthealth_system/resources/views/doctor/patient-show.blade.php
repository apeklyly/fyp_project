<x-app-layout>
    <div class="content-header">
        <h1>Patient Overview</h1>
        <p>Viewing details and health history for {{ $user->full_name }}.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2>Patient Summary</h2>
    <a href="{{ route('doctor.messages.show', $user->id) }}" class="btn btn-primary">
        Send Message
    </a>
</div>
        <div class="profile-details">
            <div class="detail-item">
                <span class="detail-label">Full Name</span>
                <span class="detail-value">{{ $user->full_name }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Age</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($user->birth_date)->age }} years old</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Email Address</span>
                <span class="detail-value">{{ $user->email }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Phone Number</span>
                <span class="detail-value">{{ $user->phone_number }}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Health Record History</h2>
        <div class="records-list">
            @forelse($records as $record)
                <div class="record-card">
                    <div class="record-header">
                        <strong>Submitted:</strong> {{ $record->created_at->format('d M Y, h:i A') }}
                    </div>
                    <div class="record-vitals">
                        <div><span>Heart Rate</span>{{ $record->heart_rate }} bpm</div>
                        <div><span>Blood Pressure</span>{{ $record->systolic_pressure }}/{{ $record->diastolic_pressure }} mmHg</div>
                        <div><span>Cholesterol</span>{{ $record->cholesterol ?? 'N/A' }} mg/dL</div>
                        <div><span>Blood Sugar</span>{{ $record->blood_sugar_value }} {{ $record->blood_sugar_unit }}</div>
                    </div>
                    <div class="record-footer">
                        <a href="{{ route('doctor.record.show', $record->id) }}" class="btn btn-secondary btn-sm">View AI Analysis</a>
                    </div>
                </div>
            @empty
                <p>No health records have been submitted by this patient yet.</p>
            @endforelse
        </div>
        <div style="margin-top: 2rem;">
            {{ $records->links('vendor.pagination.simple-custom') }}
        </div>
    </div>
</x-app-layout>