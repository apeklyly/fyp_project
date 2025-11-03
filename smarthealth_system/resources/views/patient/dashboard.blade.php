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
            <h3>Mean Pressure Trend (Last 30 Days)</h3>
            <canvas id="bpChart"></canvas>
        </div>
        
       <div class="card chart-container">
    <h3>Blood Sugar Trend (Last 30 Days)</h3>
    <canvas id="bloodSugarChart"></canvas>
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
    // --- Data from Controller ---
    const chartLabels = @json($chartLabels);
    const mapData = @json($mapData);
    const bloodSugarData = @json($bloodSugarData);
    const cholesterolData = @json($cholesterolData);

    // --- 1. BP (MAP) Chart ---
    const mapCtx = document.getElementById('bpChart');
    if (mapCtx && chartLabels.length > 0) {
        new Chart(mapCtx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Mean Arterial Pressure (MAP)',
                    data: mapData,
                    borderColor: '#34D399',
                    backgroundColor: 'rgba(52, 211, 153, 0.1)',
                    fill: true,
                    tension: 0.1,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: false, title: { display: true, text: 'Pressure (mmHg)' } } },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: { label: (context) => ` MAP: ${context.raw} mmHg` }
                    }
                }
            }
        });
    }

    // --- 2. Blood Sugar Chart ---
    const sugarCtx = document.getElementById('bloodSugarChart');
    if (sugarCtx && chartLabels.length > 0) {
        new Chart(sugarCtx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Blood Sugar',
                    data: bloodSugarData,
                    borderColor: '#FBBF24', // Yellow
                    backgroundColor: 'rgba(251, 191, 36, 0.1)',
                    fill: true,
                    tension: 0.1,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: false, title: { display: true, text: 'mg/dL' } } },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: { label: (context) => ` Blood Sugar: ${context.raw} mg/dL` }
                    }
                }
            }
        });
    }

    // --- 3. Cholesterol Chart ---
    const cholCtx = document.getElementById('cholesterolChart');
    if (cholCtx && chartLabels.length > 0) {
        new Chart(cholCtx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Cholesterol',
                    data: cholesterolData,
                    borderColor: '#EF4444', // Red
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: true,
                    tension: 0.1,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: false, title: { display: true, text: 'mg/dL' } } },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: { label: (context) => ` Cholesterol: ${context.raw} mg/dL` }
                    }
                }
            }
        });
    }
});
</script>
</x-app-layout>