<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('health_guidelines', function (Blueprint $table) {
        $table->id();
        $table->string('metric')->unique(); // e.g., 'heart_rate_danger_low', 'bp_systolic_danger_high'
        $table->string('name');             // e.g., 'Heart Rate (Danger Low)'
        $table->integer('value');           // e.g., 60
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_guidelines');
    }
};
