<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migration for table 'grades' (Data Penilaian Rapor Kurikulum Merdeka).
     */
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. "GRD-10A-INF-SIS001"
            $table->string('student_id')->index();
            $table->string('subject_id')->index();
            $table->string('classroom_id')->index();
            $table->decimal('tp1', 5, 2)->default(0);
            $table->decimal('tp2', 5, 2)->default(0);
            $table->decimal('tp3', 5, 2)->default(0);
            $table->decimal('tp4', 5, 2)->default(0);
            $table->decimal('sts', 5, 2)->default(0); // Sumatif Tengah Semester
            $table->decimal('sas', 5, 2)->default(0); // Sumatif Akhir Semester
            $table->decimal('practical_exam', 5, 2)->default(0); // Ujian Praktek (Rentang 0 - 100)
            $table->decimal('final_score', 5, 2)->default(0); // Nilai Akhir
            $table->text('competency_achievement')->nullable(); // Deskripsi Capaian Kompetensi
            $table->string('semester', 20)->default('Ganjil');
            $table->string('academic_year', 30)->default('2026/2027');
            $table->timestamps();

            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');

            $table->foreign('subject_id')
                ->references('id')
                ->on('subjects')
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
        Schema::dropIfExists('grades');
    }
};
