<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\ProfilSekolah;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Absensi;
use App\Models\PenilaianRapor;

/*
|--------------------------------------------------------------------------
| API Routes - SiApgus
|--------------------------------------------------------------------------
*/

Route::get('/health', function () {
    return response()->json([
        'status' => 'online',
        'framework' => 'Laravel 11',
        'app' => 'SiApgus - Sistem Aplikasi Guru Sekolah',
        'time' => now()->toIso8601String()
    ]);
});

Route::get('/database', function () {
    return response()->json([
        'status' => 'success',
        'profile' => ProfilSekolah::first(),
        'siswa' => Siswa::all(),
        'guru' => Guru::all(),
        'mataPelajaran' => MataPelajaran::all(),
        'absensi' => Absensi::all(),
        'penilaian' => PenilaianRapor::all(),
    ]);
});
