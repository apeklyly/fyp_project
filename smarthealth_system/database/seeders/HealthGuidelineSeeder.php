<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HealthGuideline;

class HealthGuidelineSeeder extends Seeder
{
    public function run(): void
    {
        $guidelines = [
            ['metric' => 'hr_danger_low', 'name' => 'Heart Rate (Danger Low)', 'value' => 60],
            ['metric' => 'hr_normal_high', 'name' => 'Heart Rate (Normal High)', 'value' => 100],

            ['metric' => 'bp_normal_systolic', 'name' => 'Blood Pressure (Normal Systolic)', 'value' => 120],
            ['metric' => 'bp_normal_diastolic', 'name' => 'Blood Pressure (Normal Diastolic)', 'value' => 80],
            ['metric' => 'bp_elevated_systolic', 'name' => 'Blood Pressure (Elevated Systolic)', 'value' => 129],
            ['metric' => 'bp_danger_systolic', 'name' => 'Blood Pressure (Danger Systolic)', 'value' => 140],
            ['metric' => 'bp_danger_diastolic', 'name' => 'Blood Pressure (Danger Diastolic)', 'value' => 90],

            ['metric' => 'sugar_danger_low', 'name' => 'Blood Sugar (Danger Low)', 'value' => 70],
            ['metric' => 'sugar_normal_high', 'name' => 'Blood Sugar (Normal High)', 'value' => 100],
            ['metric' => 'sugar_danger_high', 'name' => 'Blood Sugar (Danger High)', 'value' => 180],

            ['metric' => 'cholesterol_normal', 'name' => 'Cholesterol (Normal)', 'value' => 200],
            ['metric' => 'cholesterol_borderline', 'name' => 'Cholesterol (Borderline High)', 'value' => 239],
            ['metric' => 'cholesterol_high', 'name' => 'Cholesterol (High)', 'value' => 240],
        ];

        foreach ($guidelines as $guideline) {
            HealthGuideline::updateOrCreate(['metric' => $guideline['metric']], $guideline);
        }
    }
}