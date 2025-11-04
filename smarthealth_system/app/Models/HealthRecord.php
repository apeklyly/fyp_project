<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HealthGuideline; 
use Illuminate\Support\Facades\Cache;

class HealthRecord extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Analyzes vitals based on the provided guide and returns a status of
     * 'good', 'intermediate', or 'danger'.
     */
    public function getVitalsStatus(): string
{
    // Get all guidelines from the cache (or database)
    $guidelines = Cache::rememberForever('health_guidelines', function () {
        return HealthGuideline::all()->keyBy('metric');
    });

    // DANGER conditions from the database
    if (
        ($this->heart_rate !== null && ($this->heart_rate < $guidelines['hr_danger_low']->value || $this->heart_rate > $guidelines['hr_normal_high']->value)) ||
        ($this->systolic_pressure !== null && $this->systolic_pressure > $guidelines['bp_danger_systolic']->value) ||
        ($this->diastolic_pressure !== null && $this->diastolic_pressure > $guidelines['bp_danger_diastolic']->value) ||
        ($this->blood_sugar_value !== null && ($this->blood_sugar_value < $guidelines['sugar_danger_low']->value || $this->blood_sugar_value > $guidelines['sugar_danger_high']->value)) ||
        ($this->cholesterol !== null && $this->cholesterol >= $guidelines['cholesterol_high']->value) ||
        in_array('Chest Pain', json_decode($this->symptoms, true) ?? [])
    ) {
        return 'danger';
    }

    // INTERMEDIATE (Elevated/Borderline) conditions
    if (
        ($this->systolic_pressure !== null && $this->systolic_pressure >= $guidelines['bp_normal_systolic']->value) ||
        ($this->diastolic_pressure !== null && $this->diastolic_pressure >= $guidelines['bp_normal_diastolic']->value) ||
        ($this->cholesterol !== null && $this->cholesterol >= $guidelines['cholesterol_normal']->value)
    ) {
        return 'intermediate';
    }

    // If none of the above, it's GOOD
    return 'good';
}
}