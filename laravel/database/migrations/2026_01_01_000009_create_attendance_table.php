<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migration for table 'attendance' (Presensi Siswa).
     */
    public function up(): void
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. "ABS-2026-11-03-SIS001"
            $table->date('date')->index();
            $table->string('student_id')->index();
            $table->string('classroom_id')->index();
            $table->enum('status', ['H', 'S', 'I', 'A'])->default('H'); // Hadir, Sakit, Izin, Alpa
            $table->string('notes')->nullable();
            $table->string('recorded_by', 150)->nullable();
            $table->timestamps();

            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');

            $table->foreign('classroom_id')
                ->references('id')
                ->on('classrooms')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
