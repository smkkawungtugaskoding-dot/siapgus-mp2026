<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\WaliKelas;
use App\Models\ProfilSekolah;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalMapel = MataPelajaran::count();
        $totalWaliKelas = WaliKelas::count();
        $profil = ProfilSekolah::first() ?? new ProfilSekolah();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalGuru',
            'totalMapel',
            'totalWaliKelas',
            'profil'
        ));
    }
}
