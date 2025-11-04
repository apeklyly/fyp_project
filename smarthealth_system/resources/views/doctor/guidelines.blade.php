<x-app-layout>
    <div class="content-header">
        <h1>System Health Guidelines</h1>
        <p>Update the reference values for the entire system. This will affect all patient guides and doctor alerts.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <form action="{{ route('doctor.guidelines.update') }}" method="POST">
            @csrf
            <h3>Heart Rate (Resting)</h3>
            <div class="guideline-grid">
                <div class="form-group">
                    <label class="form-label">Danger (Low)</label>
                    <input type="number" name="hr_danger_low" class="form-control" value="{{ $guidelines['hr_danger_low']->value }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Normal (High)</label>
                    <input type="number" name="hr_normal_high" class="form-control" value="{{ $guidelines['hr_normal_high']->value }}">
                </div>
            </div>

            <h3>Blood Pressure (mmHg)</h3>
            <div class="guideline-grid">
                <div class="form-group">
                    <label class="form-label">Normal (Systolic)</label>
                    <input type="number" name="bp_normal_systolic" class="form-control" value="{{ $guidelines['bp_normal_systolic']->value }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Normal (Diastolic)</label>
                    <input type="number" name="bp_normal_diastolic" class="form-control" value="{{ $guidelines['bp_normal_diastolic']->value }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Elevated (Systolic)</label>
                    <input type="number" name="bp_elevated_systolic" class="form-control" value="{{ $guidelines['bp_elevated_systolic']->value }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Danger (Systolic)</label>
                    <input type="number" name="bp_danger_systolic" class="form-control" value="{{ $guidelines['bp_danger_systolic']->value }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Danger (Diastolic)</label>
                    <input type="number" name="bp_danger_diastolic" class="form-control" value="{{ $guidelines['bp_danger_diastolic']->value }}">
                </div>
            </div>

            <h3>Blood Sugar (mg/dL)</h3>
            <div class="guideline-grid">
                <div class="form-group">
                    <label class="form-label">Danger (Low)</label>
                    <input type="number" name="sugar_danger_low" class="form-control" value="{{ $guidelines['sugar_danger_low']->value }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Normal (High)</label>
                    <input type="number" name="sugar_normal_high" class="form-control" value="{{ $guidelines['sugar_normal_high']->value }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Danger (High)</label>
                    <input type="number" name="sugar_danger_high" class="form-control" value="{{ $guidelines['sugar_danger_high']->value }}">
                </div>
            </div>

            <h3>Total Cholesterol (mg/dL)</h3>
            <div class="guideline-grid">
                <div class="form-group">
                    <label class="form-label">Normal (Max)</label>
                    <input type="number" name="cholesterol_normal" class="form-control" value="{{ $guidelines['cholesterol_normal']->value }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Borderline (Max)</label>
                    <input type="number" name="cholesterol_borderline" class="form-control" value="{{ $guidelines['cholesterol_borderline']->value }}">
                </div>
                 <div class="form-group">
                    <label class="form-label">High</label>
                    <input type="number" name="cholesterol_high" class="form-control" value="{{ $guidelines['cholesterol_high']->value }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Guidelines</button>
        </form>
    </div>
</x-app-layout>