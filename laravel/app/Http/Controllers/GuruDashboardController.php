<?php

namespace App\Http\Controllers;

use App\Models\WaliKelas;
use App\Models\GuruMapel;
use App\Models\ProfilSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Cek apakah guru adalah wali kelas
        $waliKelas = WaliKelas::where('guru_id', $user->id)
            ->orWhere('nama_guru', $user->full_name)
            ->first();

        // Jadwal Mengajar Guru
        $mapelAjar = GuruMapel::where('guru_id', $user->id)->get();
        $profil = ProfilSekolah::first() ?? new ProfilSekolah();

        return view('guru.dashboard', compact('user', 'waliKelas', 'mapelAjar', 'profil'));
    }
}
