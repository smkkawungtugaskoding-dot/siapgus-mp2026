<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migration for table 'classrooms'.
     */
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. "10-A", "11-B"
            $table->string('grade_level', 20); // e.g. "10", "11", "12"
            $table->string('rombel', 20); // e.g. "A", "B", "1"
            $table->string('name', 100); // e.g. "Kelas 10-A"
            $table->string('phase', 10)->nullable()->default('E'); // Fase E, F
            $table->string('academic_year', 30)->default('2026/2027');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
