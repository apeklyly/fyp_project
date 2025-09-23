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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('id');
            $table->string('full_name')->after('username');
            $table->date('birth_date')->nullable()->after('email');
            $table->string('phone_number')->nullable()->after('birth_date');
            $table->dropColumn('name'); // Remove the old 'name' column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name');
            $table->dropColumn(['username', 'full_name', 'birth_date', 'phone_number']);
        });
    }

};
