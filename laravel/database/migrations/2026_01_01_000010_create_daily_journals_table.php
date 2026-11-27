<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migration for table 'daily_journals' (Jurnal Pembelajaran Harian Guru).
     */
    public function up(): void
    {
        Schema::create('daily_journals', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. "JRN-2026-11-03-01"
            $table->date('date')->index();
            $table->string('teacher_id')->index();
            $table->string('subject_id')->index();
            $table->string('classroom_id')->index();
            $table->string('teaching_hours', 50)->nullable(); // e.g. "Jam 1-2"
            $table->text('topic'); // Materi / Capaian Pembelajaran
            $table->string('attendance_summary')->nullable(); // e.g. "H: 34, S: 1, I: 1, A: 0"
            $table->text('reflection_notes')->nullable(); // Catatan & refleksi KBM
            $table->timestamps();

            $table->foreign('teacher_id')
                ->references('id')
                ->on('teachers')
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
        Schema::dropIfExists('daily_journals');
    }
};
