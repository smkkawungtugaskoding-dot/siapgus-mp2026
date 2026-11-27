<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migration for table 'homeroom_assignments' (Penetapan Wali Kelas).
     */
    public function up(): void
    {
        Schema::create('homeroom_assignments', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. "WK-10A"
            $table->string('teacher_id')->index();
            $table->string('classroom_id')->index();
            $table->string('academic_year', 30)->default('2026/2027');
            $table->string('semester', 20)->default('Ganjil');
            $table->timestamps();

            $table->foreign('teacher_id')
                ->references('id')
                ->on('teachers')
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
        Schema::dropIfExists('homeroom_assignments');
    }
};
