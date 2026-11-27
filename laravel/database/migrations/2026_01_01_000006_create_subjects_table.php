<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migration for table 'subjects'.
     */
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->string('id')->primary(); // Kode Unik Mapel e.g. "MP-INF"
            $table->string('code', 30)->unique();
            $table->string('name', 150);
            $table->integer('kktp')->default(75);
            $table->string('category', 50)->nullable()->default('Umum'); // Umum, Kejuruan, Pilihan
            $table->string('phase', 10)->nullable()->default('E');
            $table->integer('hours_per_week')->default(2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
