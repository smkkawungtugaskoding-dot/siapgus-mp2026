<?php

namespace App\Http\Controllers;

use App\Models\ProfilSekolah;
use Illuminate\Http\Request;

class ProfilSekolahController extends Controller
{
    public function index()
    {
        $profil = ProfilSekolah::first() ?? new ProfilSekolah();
        return view('admin.profil', compact('profil'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'nama_sekolah' => 'required|string|max:200',
            'npsn' => 'nullable|string|max:50',
            'alamat_sekolah' => 'nullable|string',
            'telp' => 'nullable|string|max:50',
            'kepala_nama' => 'nullable|string|max:150',
            'kepala_nip' => 'nullable|string|max:50',
            'tahun_ajaran' => 'required|string|max:50',
            'semester' => 'required|string|max:50',
            'kurikulum' => 'required|string|max:50',
            'logo_url' => 'nullable|string',
        ]);

        $profil = ProfilSekolah::first();
        if ($profil) {
            $profil->update($data);
        } else {
            ProfilSekolah::create($data);
        }

        return redirect()->route('admin.profil')->with('success', 'Profil sekolah dan logo favicon berhasil disimpan!');
    }
}
