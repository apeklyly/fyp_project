<x-app-layout>
    <div class="content-header">
        <h1>Dashboard</h1>
        <p>Welcome back, {{ Auth::user()->full_name }}.</p>
    </div>

    <div class="dashboard-grid">
        <div class="stat-card">
            <h4><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M240,94c0,70-103.79,126.66-108.21,129a16,16,0,0,1-13.8,0C113.79,220.66,8,164,8,94A54,54,0,0,1,62,40a58,58,0,0,1,58,40.58A58,58,0,0,1,178,40,54,54,0,0,1,240,94Z"></path></svg> Heart Rate</h4>
            <p>{{ $latestRecord->heart_rate ?? 'N/A' }} <span>bpm</span></p>
        </div>
        <div class="stat-card">
            <h4><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M168,24H88A56,56,0,0,0,32,80v96a56,56,0,0,0,56,56h80a56,56,0,0,0,56-56V80A56,56,0,0,0,168,24Zm40,152a40,40,0,0,1-40,40H88a40,40,0,0,1-40-40V80A40,40,0,0,1,88,40h80a40,40,0,0,1,40,40Zm-72-88a12,12,0,1,1-12-12A12,12,0,0,1,136,88Z"></path></svg> Blood Pressure</h4>
            <p>{{ $latestRecord ? $latestRecord->systolic_pressure . ' / ' . $latestRecord->diastolic_pressure : 'N/A' }} <span>mmHg</span></p>
        </div>
        <div class="stat-card">
            <h4><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M208,32H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32ZM184,104a8,8,0,0,1-8,8H152v24a8,8,0,0,1-16,0V112H112a8,8,0,0,1,0-16h24V72a8,8,0,0,1,16,0V96h24A8,8,0,0,1,184,104Z"></path></svg> Blood Sugar</h4>
            <p>{{ $latestRecord->blood_sugar_value ?? 'N/A' }} <span>{{ $latestRecord->blood_sugar_unit ?? '' }}</span></p>
        </div>
        <div class="stat-card">
            <h4><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 256 256"><path d="M232,88A16,16,0,0,0,216,72H175.62A87.81,87.81,0,0,0,135.85,35a16,16,0,0,0-26,21.14A87.81,87.81,0,0,0,80.38,72H40A16,16,0,0,0,24,88v0a16,16,0,0,0,14.4,15.91,88.08,88.08,0,0,0,30.51,60.85,87.8,87.8,0,0,0-10.36,44.79A16,16,0,0,0,74.5,224h107a16,16,0,0,0,15.95-14.45,87.8,87.8,0,0,0-10.36-44.79,88.08,88.08,0,0,0,30.51-60.85A16,16,0,0,0,232,88Z"></path></svg> Cholesterol</h4>
            <p>{{ $latestRecord->cholesterol ?? 'N/A' }} <span>mg/dL</span></p>
        </div>

        <div class="card chart-container">
            <h3>Blood Pressure Trend (Last 30 Days)</h3>
            <canvas id="bpChart"></canvas>
        </div>
        
        <div class="card chart-container">
            <h3>Blood Sugar Trend (Last 30 Days)</h3>
            <canvas id="sugarChart"></canvas>
        </div>

        <div class="card chart-container">
            <h3>Cholesterol Trend (Last 30 Days)</h3>
            <canvas id="cholesterolChart"></canvas>
        </div>

        <div class="card">
            <h3>Upcoming Appointment</h3>
            @if($upcomingAppointment)
                <div class="appointment-details">
                    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($upcomingAppointment->appointment_date)->format('F j, Y \a\t h:i A') }}</p>
                    <p><strong>Doctor:</strong> Dr. {{ $upcomingAppointment->doctor->full_name }}</p>
                    <a href="{{ route('patient.appointments.index') }}" class="btn btn-secondary btn-sm" style="margin-top: 1rem;">View All Appointments</a>
                </div>
            @else
                <p>You have no upcoming appointments.</p>
                <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary btn-sm" style="margin-top: 1rem;">Book a New Appointment</a>
            @endif
        </div>

        <div class="card">
    <h3>Quick Actions</h3>
    <div class="actions-list">
        <a href="{{ route('patient.checkup.create') }}" class="btn btn-primary">Submit New Health Data</a>
        
        <a href="{{ route('patient.messages.index') }}" class="btn btn-secondary">
            View Messages 
            @if($unreadMessagesCount > 0)
                <span class="action-badge">{{ $unreadMessagesCount }}</span>
            @endif
        </a>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const records = @json($healthRecordsForChart);
            
            if (records.length > 0) {
                const dates = records.map(record => new Date(record.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));

                // Data for Blood Pressure Chart
                const systolicData = records.map(record => record.systolic_pressure);
                const diastolicData = records.map(record => record.diastolic_pressure);
                const bpCtx = document.getElementById('bpChart').getContext('2d');
                new Chart(bpCtx, {
                    type: 'line',
                    data: {
                        labels: dates,
                        datasets: [
                            { label: 'Systolic', data: systolicData, borderColor: '#111827', tension: 0.1, borderWidth: 2 },
                            { label: 'Diastolic', data: diastolicData, borderColor: '#34D399', tension: 0.1, borderWidth: 2 }
                        ]
                    }, options: { responsive: true, maintainAspectRatio: false }
                });

                // Data for Blood Sugar Chart
                const bloodSugarData = records.map(record => record.blood_sugar_value);
                const sugarCtx = document.getElementById('sugarChart').getContext('2d');
                new Chart(sugarCtx, {
                    type: 'line',
                    data: {
                        labels: dates,
                        datasets: [{ label: 'Blood Sugar (mg/dL)', data: bloodSugarData, borderColor: '#FBBF24', tension: 0.1, borderWidth: 2 }]
                    }, options: { responsive: true, maintainAspectRatio: false }
                });

                // Data for Cholesterol Chart
                const cholesterolData = records.map(record => record.cholesterol);
                const cholesterolCtx = document.getElementById('cholesterolChart').getContext('2d');
                new Chart(cholesterolCtx, {
                    type: 'line',
                    data: {
                        labels: dates,
                        datasets: [{ label: 'Cholesterol (mg/dL)', data: cholesterolData, borderColor: '#3B82F6', tension: 0.1, borderWidth: 2 }]
                    }, options: { responsive: true, maintainAspectRatio: false }
                });

            } else {
                document.querySelectorAll('.chart-container').forEach(container => {
                    container.innerHTML = '<h3>Health Trend</h3><p>No recent health data available to display a trend.</p>';
                });
            }
        });
    </script>
</x-app-layout>