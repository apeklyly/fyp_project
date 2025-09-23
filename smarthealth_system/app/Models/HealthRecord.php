<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    // Add this to allow mass assignment
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
