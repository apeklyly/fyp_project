<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Health Record - {{ $record->user->full_name }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.6;
            color: #333;
            background-color: #fff;
        }
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            border: 1px solid #ccc;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            font-size: 14px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            border-bottom: 1px solid #eee;
            padding-bottom: 0.5rem;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .info-item p {
            margin: 0;
        }
        .info-item .label {
            font-weight: bold;
        }
        .vitals-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            text-align: center;
        }
        .vitals-item {
            padding: 1rem;
            border: 1px solid #eee;
            border-radius: 8px;
        }
        .vitals-item .value {
            font-size: 24px;
            font-weight: bold;
        }
        .vitals-item .label {
            font-size: 14px;
            color: #666;
        }
        .footer {
            text-align: center;
            margin-top: 3rem;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 1rem;
        }
        .print-button {
            display: block;
            width: 100px;
            margin: 2rem auto;
            padding: 0.5rem 1rem;
            text-align: center;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        @media print {
            .print-button {
                display: none;
            }
            .container {
                border: none;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>SmartHealth System</h1>
            <p>Confidential Medical Record</p>
        </div>

        <div class="section-title">Patient Information</div>
        <div class="info-grid">
            <div class="info-item">
                <p class="label">Full Name:</p>
                <p>{{ $record->user->full_name }}</p>
            </div>
            <div class="info-item">
                <p class="label">Date of Birth:</p>
                <p>{{ \Carbon\Carbon::parse($record->user->birth_date)->format('F j, Y') }}</p>
            </div>
        </div>

        <div class="section-title">Record Details</div>
        <div class="info-grid">
            <div class="info-item">
                <p class="label">Record Submitted On:</p>
                <p>{{ $record->created_at->format('F j, Y \a\t g:i A') }}</p>
            </div>
        </div>
        
        <div class="section-title">Vital Signs</div>
        <div class="vitals-grid">
            <div class="vitals-item">
                <p class="value">{{ $record->heart_rate }}</p>
                <p class="label">Heart Rate (bpm)</p>
            </div>
            <div class="vitals-item">
                <p class="value">{{ $record->systolic_pressure }}/{{ $record->diastolic_pressure }}</p>
                <p class="label">Blood Pressure (mmHg)</p>
            </div>
            <div class="vitals-item">
                <p class="value">{{ $record->blood_sugar_value }}</p>
                <p class="label">Blood Sugar ({{ $record->blood_sugar_unit }})</p>
            </div>
             <div class="vitals-item">
                <p class="value">{{ $record->cholesterol ?? 'N/A' }}</p>
                <p class="label">Cholesterol (mg/dL)</p>
            </div>
        </div>

        <div class="section-title">Symptoms & Notes</div>
        <p><strong>Reported Symptoms:</strong> {{ implode(', ', json_decode($record->symptoms, true)) }}</p>
        <p><strong>Patient Notes:</strong> {{ $record->notes ?: 'None' }}</p>

        @php
            $aiData = json_decode($record->recommendation, true);
        @endphp
        
        @if($aiData && !isset($aiData['error']))
            <div class="section-title">AI Health Analysis</div>
            <p><strong>Overall Status:</strong> {{ $aiData['overall_status'] ?? 'N/A' }}</p>
            <p><strong>Key Advice:</strong></p>
            <ul>
                @foreach($aiData['key_advice'] ?? [] as $advice)
                    <li>{{ $advice }}</li>
                @endforeach
            </ul>
        @endif

        <div class="footer">
            <p>This report was generated by the SmartHealth System on {{ now()->format('F j, Y') }}.</p>
            <p>This is not a substitute for professional medical advice. Always consult a doctor for diagnosis.</p>
        </div>
    </div>

    <button class="print-button" onclick="window.print()">Print</button>

</body>
</html>