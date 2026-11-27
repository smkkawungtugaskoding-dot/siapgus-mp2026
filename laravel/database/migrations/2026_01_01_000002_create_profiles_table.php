<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migration for table 'profiles' (for school settings).
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('school_name')->default('SMK KAWUNG SURABAYA');
            $table->string('npsn', 30)->nullable()->default('20532189');
            $table->text('address')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('website', 150)->nullable();
            $table->string('principal_name')->nullable();
            $table->string('principal_nip', 50)->nullable();
            $table->string('academic_year', 30)->default('2026/2027');
            $table->string('semester', 20)->default('Ganjil');
            $table->string('curriculum', 50)->default('Kurikulum Merdeka');
            $table->longText('logo_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
