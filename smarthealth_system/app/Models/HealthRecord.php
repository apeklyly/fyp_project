<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        // DANGER conditions from the guide
        if (
            ($this->heart_rate !== null && ($this->heart_rate < 60 || $this->heart_rate > 100)) ||
            ($this->systolic_pressure !== null && $this->systolic_pressure > 140) ||
            ($this->diastolic_pressure !== null && $this->diastolic_pressure > 90) ||
            ($this->blood_sugar_value !== null && ($this->blood_sugar_value < 70 || $this->blood_sugar_value > 180)) ||
            ($this->cholesterol !== null && $this->cholesterol >= 240) ||
            in_array('Chest Pain', json_decode($this->symptoms, true) ?? [])
        ) {
            return 'danger';
        }

        // INTERMEDIATE (Elevated/Borderline) conditions from the guide
        if (
            // Blood Pressure: Elevated
            ($this->systolic_pressure !== null && $this->systolic_pressure >= 120) ||
            ($this->diastolic_pressure !== null && $this->diastolic_pressure >= 80) ||
            // Cholesterol: Borderline
            ($this->cholesterol !== null && $this->cholesterol >= 200)
        ) {
            return 'intermediate';
        }

        // If none of the above, it's GOOD
        return 'good';
    }
}