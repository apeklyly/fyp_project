<x-app-layout>
    <div class="content-header">
        <h1>New Medical Checkup</h1>
        <p>Please enter your latest health metrics accurately.</p>
    </div>

    <div class="card reference-guide-card">
        <h4>Health Metrics Reference Guide</h4>
        <div class="guide-grid">
            <div class="metric-guide">
                <h5>Heart Rate (Resting)</h5>
                <ul>
                    <li><span class="range-normal">Normal:</span> 60 - 100 bpm</li>
                    <li><span class="range-danger">Danger Zone:</span> Below 60 or Above 100 bpm</li>
                </ul>
            </div>
            <div class="metric-guide">
                <h5>Blood Pressure (mmHg)</h5>
                <ul>
                    <li><span class="range-normal">Normal:</span> Below 120 / 80</li>
                    <li><span class="range-elevated">Elevated:</span> 120-129 / Below 80</li>
                    <li><span class="range-danger">Danger Zone:</span> Above 140 / 90</li>
                </ul>
            </div>
            <div class="metric-guide">
                <h5>Blood Sugar (mg/dL)</h5>
                <ul>
                    <li><span class="range-normal">Normal (Fasting):</span> 70 - 100</li>
                    <li><span class="range-danger">Danger Zone:</span> Below 70 or Above 180</li>
                </ul>
            </div>
        </div>
        <p class="disclaimer">*These are general guidelines for adults. Consult your doctor for personalized medical advice.</p>
    </div>
    <div class="card">
        <form action="{{ route('patient.checkup.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="heart_rate" class="form-label">Heart Rate (bpm)</label>
                <input type="number" id="heart_rate" name="heart_rate" class="form-control" required placeholder="e.g., 72">
            </div>

            <div class="form-group">
                <label class="form-label">Blood Pressure (Systolic / Diastolic)</label>
                <div style="display: flex; gap: 1rem;">
                    <input type="number" name="systolic_pressure" class="form-control" required placeholder="Systolic, e.g., 120">
                    <input type="number" name="diastolic_pressure" class="form-control" required placeholder="Diastolic, e.g., 80">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Blood Sugar</label>
                 <div style="display: flex; gap: 1rem;">
                    <input type="number" step="0.1" name="blood_sugar_value" class="form-control" required placeholder="e.g., 95">
                    <select name="blood_sugar_unit" class="form-select">
                        <option value="mg/dL">mg/dL</option>
                        <option value="mmol/L">mmol/L</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Symptoms (check all that apply)</label>
                <div class="form-checkbox-group">
                    @php $symptoms = ['None', 'Fever', 'Cough', 'Headache', 'Fatigue', 'Dizziness', 'Nausea', 'Chest Pain', 'Difficulty Breathing']; @endphp
                    @foreach($symptoms as $symptom)
                    <div class="form-checkbox-item">
                        <input type="checkbox" id="{{ str_replace(' ', '', $symptom) }}" name="symptoms[]" value="{{ $symptom }}" class="form-checkbox">
                        <label for="{{ str_replace(' ', '', $symptom) }}">{{ $symptom }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
             <div class="form-group">
                <label for="notes" class="form-label">Additional Notes (optional)</label>
                <textarea id="notes" name="notes" rows="4" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit Data</button>
        </form>
    </div>
</x-app-layout>