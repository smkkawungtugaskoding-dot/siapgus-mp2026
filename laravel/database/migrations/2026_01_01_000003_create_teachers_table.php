<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migration for table 'teachers'.
     */
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id')->nullable()->index();
            $table->string('nik_nuptk', 50)->nullable()->index();
            $table->string('name', 150);
            $table->enum('gender', ['L', 'P'])->default('L');
            $table->string('email', 100)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('position', 100)->nullable()->default('Guru Pengajar');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
