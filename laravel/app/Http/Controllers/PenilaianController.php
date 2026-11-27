<?php

namespace App\Http\Controllers;

use App\Models\PenilaianRapor;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use App\Models\GuruMapel;
use App\Models\ProfilSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $profil = ProfilSekolah::first() ?? new ProfilSekolah();

        // Daftar mapel yang diajar oleh guru ini
        $guruMapelList = GuruMapel::where('guru_id', $user->id)->get();
        $allMapel = MataPelajaran::all();

        $selectedKelas = $request->input('kelas', '');
        $selectedRombel = $request->input('rombel', '');
        $selectedMapel = $request->input('mapel_id', '');

        $siswaList = collect();
        $penilaianList = collect();

        // Hanya tampilkan siswa setelah kelas, rombel, dan mapel dipilih
        if (!empty($selectedKelas) && !empty($selectedRombel) && !empty($selectedMapel)) {
            $siswaList = Siswa::where('kelas', $selectedKelas)
                ->where('rombel', $selectedRombel)
                ->orderBy('nama', 'asc')
                ->get();

            $penilaianList = PenilaianRapor::where('kelas', $selectedKelas)
                ->where('rombel', $selectedRombel)
                ->where('mapel_id', $selectedMapel)
                ->get()
                ->keyBy('siswa_id');
        }

        $activeMapel = MataPelajaran::find($selectedMapel);

        return view('guru.penilaian', compact(
            'guruMapelList',
            'allMapel',
            'selectedKelas',
            'selectedRombel',
            'selectedMapel',
            'activeMapel',
            'siswaList',
            'penilaianList',
            'profil'
        ));
    }

    public function simpanBaris(Request $request)
    {
        $data = $request->validate([
            'siswa_id' => 'required|string',
            'mapel_id' => 'required|string',
            'kelas' => 'required|string',
            'rombel' => 'required|string',
            'tp1' => 'nullable|numeric|min:0|max:100',
            'tp2' => 'nullable|numeric|min:0|max:100',
            'tp3' => 'nullable|numeric|min:0|max:100',
            'tp4' => 'nullable|numeric|min:0|max:100',
            'sts' => 'nullable|numeric|min:0|max:100',
            'sas' => 'nullable|numeric|min:0|max:100',
            'ujian_praktek' => 'nullable|numeric|min:0|max:100',
            'capaian' => 'nullable|string',
        ]);

        $id = "NIL-{$data['kelas']}{$data['rombel']}-{$data['mapel_id']}-{$data['siswa_id']}";

        $penilaian = PenilaianRapor::updateOrCreate(
            ['id' => $id],
            [
                'siswa_id' => $data['siswa_id'],
                'mapel_id' => $data['mapel_id'],
                'kelas' => $data['kelas'],
                'rombel' => $data['rombel'],
                'tp1' => $data['tp1'] ?? 0,
                'tp2' => $data['tp2'] ?? 0,
                'tp3' => $data['tp3'] ?? 0,
                'tp4' => $data['tp4'] ?? 0,
                'sts' => $data['sts'] ?? 0,
                'sas' => $data['sas'] ?? 0,
                'ujian_praktek' => $data['ujian_praktek'] ?? 0,
                'capaian' => $data['capaian'] ?? '',
            ]
        );

        // Kalkulasi Nilai Akhir
        $na = $penilaian->hitungNilaiAkhir();
        $penilaian->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Nilai siswa berhasil disimpan!',
                'nilai_akhir' => $na,
                'ujian_praktek' => $penilaian->ujian_praktek,
                'siswa_id' => $data['siswa_id']
            ]);
        }

        return back()->with('success', 'Nilai siswa berhasil disimpan!')->with('saved_siswa_id', $data['siswa_id']);
    }
}
