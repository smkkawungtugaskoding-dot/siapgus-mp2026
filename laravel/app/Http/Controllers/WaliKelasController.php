<?php

namespace App\Http\Controllers;

use App\Models\WaliKelas;
use App\Models\Guru;
use App\Models\ProfilSekolah;
use Illuminate\Http\Request;

class WaliKelasController extends Controller
{
    public function index()
    {
        $waliList = WaliKelas::orderBy('kode_kelas', 'asc')->orderBy('rombel', 'asc')->get();
        $guruList = Guru::orderBy('nama', 'asc')->get();
        $profil = ProfilSekolah::first() ?? new ProfilSekolah();

        return view('admin.wali_kelas', compact('waliList', 'guruList', 'profil'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'guru_id' => 'required|string',
            'kode_kelas' => 'required|string',
            'rombel' => 'required|string',
            'tahun_ajaran' => 'required|string',
            'semester' => 'required|string',
        ]);

        $guru = Guru::findOrFail($data['guru_id']);
        $id = "WK-{$data['kode_kelas']}{$data['rombel']}";

        WaliKelas::updateOrCreate(
            ['id' => $id],
            [
                'guru_id' => $guru->id,
                'nama_guru' => $guru->nama,
                'kode_kelas' => $data['kode_kelas'],
                'rombel' => $data['rombel'],
                'tahun_ajaran' => $data['tahun_ajaran'],
                'semester' => $data['semester'],
            ]
        );

        return redirect()->route('admin.wali_kelas')->with('success', "Wali kelas untuk {$data['kode_kelas']}-{$data['rombel']} berhasil ditetapkan!");
    }

    public function destroy($id)
    {
        WaliKelas::destroy($id);
        return redirect()->route('admin.wali_kelas')->with('success', 'Penetapan wali kelas berhasil dihapus.');
    }
}
