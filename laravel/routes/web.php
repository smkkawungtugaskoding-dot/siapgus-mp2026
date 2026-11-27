<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\GuruDashboardController;
use App\Http\Controllers\ProfilSekolahController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\WaliKelasController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\RaporController;

/*
|--------------------------------------------------------------------------
| Web Routes - SiApgus (Sistem Aplikasi Guru Sekolah)
|--------------------------------------------------------------------------
| Mendukung otentikasi Laravel Breeze dengan otorisasi berbasis role Admin & Guru
*/

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('guru.dashboard');
    }
    return redirect()->route('login');
});

// Memuat Route Autentikasi Laravel Breeze (login, logout)
require __DIR__.'/auth.php';

// -------------------------------------------------------------
// AREA ADMINISTRATOR SEKOLAH (ROLE: ADMIN)
// -------------------------------------------------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Profil Sekolah & Dynamic Favicon
    Route::get('/profil', [ProfilSekolahController::class, 'index'])->name('profil');
    Route::post('/profil', [ProfilSekolahController::class, 'update'])->name('profil.update');

    // Manajemen Siswa
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa');
    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::delete('/siswa/{id}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

    // Manajemen Guru
    Route::get('/guru', [GuruController::class, 'index'])->name('guru');
    Route::post('/guru', [GuruController::class, 'store'])->name('guru.store');
    Route::delete('/guru/{id}', [GuruController::class, 'destroy'])->name('guru.destroy');

    // Mata Pelajaran
    Route::get('/mapel', [MataPelajaranController::class, 'index'])->name('mapel');
    Route::post('/mapel', [MataPelajaranController::class, 'store'])->name('mapel.store');
    Route::delete('/mapel/{id}', [MataPelajaranController::class, 'destroy'])->name('mapel.destroy');

    // Penetapan Wali Kelas
    Route::get('/wali-kelas', [WaliKelasController::class, 'index'])->name('wali_kelas');
    Route::post('/wali-kelas', [WaliKelasController::class, 'store'])->name('wali_kelas.store');
    Route::delete('/wali-kelas/{id}', [WaliKelasController::class, 'destroy'])->name('wali_kelas.destroy');
});

// -------------------------------------------------------------
// AREA GURU MATA PELAJARAN & WALI KELAS (ROLE: GURU)
// -------------------------------------------------------------
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');

    // Penilaian Rapor (dengan Ujian Praktek & Cetak Rapor Sejajar)
    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian');
    Route::post('/penilaian/simpan-baris', [PenilaianController::class, 'simpanBaris'])->name('penilaian.simpan');

    // Absensi Siswa & Rekap Bulanan (HANYA UNTUK WALI KELAS YANG DITETAPKAN)
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi');
    Route::post('/absensi', [AbsensiController::class, 'simpan'])->name('absensi.simpan');
    Route::get('/absensi/cetak-rekap', [AbsensiController::class, 'cetakRekap'])->name('absensi.cetak');

    // Cetak Rapor Sisipan (STS) & Akhir (SAS) dengan TTE Elektronik
    Route::get('/rapor/sisipan/{siswaId}', [RaporController::class, 'cetakSisipan'])->name('rapor.sisipan');
    Route::get('/rapor/akhir/{siswaId}', [RaporController::class, 'cetakAkhir'])->name('rapor.akhir');
});
