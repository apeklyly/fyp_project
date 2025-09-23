<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('health_records', function (Blueprint $table) {
            // Rename old blood_sugar column
            $table->renameColumn('blood_sugar', 'blood_sugar_value');

            // Add new columns
            $table->string('blood_sugar_unit')->after('blood_sugar_value'); // To store 'mg/dL' or 'mmol/L'
            $table->integer('systolic_pressure')->after('heart_rate');    // e.g., 120
            $table->integer('diastolic_pressure')->after('systolic_pressure'); // e.g., 80
        });
    }

    public function down(): void
    {
        Schema::table('health_records', function (Blueprint $table) {
            $table->renameColumn('blood_sugar_value', 'blood_sugar');
            $table->dropColumn(['blood_sugar_unit', 'systolic_pressure', 'diastolic_pressure']);
        });
    }
};
