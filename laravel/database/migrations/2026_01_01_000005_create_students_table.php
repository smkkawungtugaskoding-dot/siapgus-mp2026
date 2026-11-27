<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migration for table 'students'.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->string('id')->primary(); // NIS / ID Siswa
            $table->string('classroom_id')->nullable()->index();
            $table->string('nis', 30)->nullable();
            $table->string('nisn', 30)->nullable()->index();
            $table->string('name', 150);
            $table->enum('gender', ['L', 'P'])->default('L');
            $table->string('phone', 30)->nullable();
            $table->string('parent_name', 150)->nullable();
            $table->text('address')->nullable();
            $table->timestamps();

            $table->foreign('classroom_id')
                ->references('id')
                ->on('classrooms')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
