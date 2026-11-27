<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\PenilaianRapor;
use App\Models\MataPelajaran;
use App\Models\WaliKelas;
use App\Models\ProfilSekolah;
use App\Models\Absensi;
use Illuminate\Http\Request;

class RaporController extends Controller
{
    public function cetakSisipan($siswaId)
    {
        return $this->renderRapor($siswaId, 'sisipan');
    }

    public function cetakAkhir($siswaId)
    {
        return $this->renderRapor($siswaId, 'akhir');
    }

    private function renderRapor($siswaId, $mode)
    {
        $siswa = Siswa::findOrFail($siswaId);
        $profil = ProfilSekolah::first() ?? new ProfilSekolah();

        $wali = WaliKelas::where('kode_kelas', $siswa->kelas)
            ->where('rombel', $siswa->rombel)
            ->first() ?? (object)[
                'nama_guru' => 'Wali Kelas ' . $siswa->kelas . $siswa->rombel,
                'guru_id' => '-'
            ];

        // Ambil semua mata pelajaran dan nilai siswa
        $allMapel = MataPelajaran::all();
        $penilaianMap = PenilaianRapor::where('siswa_id', $siswa->id)
            ->get()
            ->keyBy('mapel_id');

        $rowsNilai = [];
        foreach ($allMapel as $idx => $m) {
            $p = $penilaianMap->get($m->id);
            $kktp = $m->kktp ?? 75;

            if ($mode === 'sisipan') {
                // Rapor Sisipan: dari Nilai STS & TP
                $nilaiUtama = $p ? ($p->sts > 0 ? $p->sts : round(($p->tp1 + $p->tp2)/2, 1)) : 0;
            } else {
                // Rapor Akhir: dari Nilai Akhir (termasuk SAS & Ujian Praktek)
                $nilaiUtama = $p ? $p->nilai_akhir : 0;
            }

            $predikat = 'Kurang';
            if ($nilaiUtama >= 90) $predikat = 'Sangat Baik (A)';
            else if ($nilaiUtama >= 80) $predikat = 'Baik (B)';
            else if ($nilaiUtama >= $kktp) $predikat = 'Cukup (C)';

            $capaian = $p ? $p->capaian : '';
            if (empty($capaian)) {
                $capaian = $nilaiUtama >= $kktp 
                    ? "Menunjukkan penguasaan yang sangat baik dalam memahami konsep pembelajaran {$m->nama}."
                    : "Perlu bimbingan dan pendalaman materi kompetensi dasar mata pelajaran {$m->nama}.";
            }

            $rowsNilai[] = [
                'no' => $idx + 1,
                'nama_mapel' => $m->nama,
                'kktp' => $kktp,
                'nilai' => $nilaiUtama,
                'predikat' => $predikat,
                'capaian' => $capaian,
            ];
        }

        // Rekap ketidakhadiran siswa
        $sakit = Absensi::where('siswa_id', $siswa->id)->where('status', 'S')->count();
        $izin  = Absensi::where('siswa_id', $siswa->id)->where('status', 'I')->count();
        $alpa  = Absensi::where('siswa_id', $siswa->id)->where('status', 'A')->count();

        // TTE QR Code Payload
        $tteKepsekPayload = "KEASLIAN DOKUMEN RAPOR ELEKTRONIK\nSekolah: {$profil->nama_sekolah}\nKepala Sekolah: {$profil->kepala_nama}\nNIP: {$profil->kepala_nip}\nSiswa: {$siswa->nama} ({$siswa->id})\nTipe Rapor: " . ($mode === 'sisipan' ? 'Sisipan (STS)' : 'Akhir Semester (SAS)') . "\nStatus: DIVERIFIKASI RESMI DIGITAL";
        $tteWaliPayload = "TANDA TANGAN ELEKTRONIK WALI KELAS\nWali Kelas: {$wali->nama_guru}\nKelas: {$siswa->kelas}-{$siswa->rombel}\nStatus: VALID DAN SAH";

        return view('guru.rapor_preview', compact(
            'siswa',
            'profil',
            'wali',
            'mode',
            'rowsNilai',
            'sakit',
            'izin',
            'alpa',
            'tteKepsekPayload',
            'tteWaliPayload'
        ));
    }
}
