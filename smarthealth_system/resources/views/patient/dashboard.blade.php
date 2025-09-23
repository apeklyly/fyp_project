<x-app-layout>
    <div class="content-header">
        <h1>Patient Dashboard</h1>
        <p>Welcome back, {{ Auth::user()->username }}! Here's a summary of your health.</p>
    </div>

    <div class="card">
        <h2>Recent Activity</h2>
        @if($latestRecord)
            <p><strong>Last Checkup:</strong> {{ $latestRecord->created_at->format('d M Y, h:i A') }}</p>
            <p><strong>Heart Rate:</strong> {{ $latestRecord->heart_rate }} bpm</p>
            <p><strong>Blood Sugar:</strong> {{ $latestRecord->blood_sugar }} mg/dL</p>
            <a href="{{ route('patient.record.show', $latestRecord->id) }}" class="btn btn-primary">View Details</a>
        @else
            <p>You have not submitted any health data yet.</p>
            <a href="{{ route('patient.checkup.create') }}" class="btn btn-secondary">Submit Your First Checkup</a>
        @endif
    </div>
</x-app-layout>