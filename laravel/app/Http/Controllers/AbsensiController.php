<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\WaliKelas;
use App\Models\ProfilSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    /**
     * Memverifikasi apakah user saat ini adalah Wali Kelas yang ditunjuk
     */
    private function getWaliKelasAuth()
    {
        $user = Auth::user();
        return WaliKelas::where('guru_id', $user->id)
            ->orWhere('nama_guru', $user->full_name)
            ->first();
    }

    public function index(Request $request)
    {
        $wali = $this->getWaliKelasAuth();

        // JIKA BUKAN WALI KELAS: Ditolak / Dilarang Akses
        if (!$wali) {
            return redirect()->route('guru.dashboard')->with('error', 
                'Akses Ditolak: Menu Absensi hanya untuk Guru yang telah ditugaskan sebagai Wali Kelas resmi.'
            );
        }

        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $mode = $request->input('mode', 'harian'); // 'harian' atau 'rekap'
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Siswa di kelas binaan wali kelas
        $siswaList = Siswa::where('kelas', $wali->kode_kelas)
            ->where('rombel', $wali->rombel)
            ->orderBy('nama', 'asc')
            ->get();

        // Data Absensi Hari Ini
        $absensiHariIni = Absensi::where('kelas', $wali->kode_kelas)
            ->where('rombel', $wali->rombel)
            ->where('tanggal', $tanggal)
            ->get()
            ->keyBy('siswa_id');

        // Rekap Bulanan
        $rekapBulanan = [];
        if ($mode === 'rekap') {
            foreach ($siswaList as $s) {
                $totalHadir = Absensi::where('siswa_id', $s->id)->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->where('status', 'H')->count();
                $totalSakit = Absensi::where('siswa_id', $s->id)->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->where('status', 'S')->count();
                $totalIzin  = Absensi::where('siswa_id', $s->id)->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->where('status', 'I')->count();
                $totalAlpa  = Absensi::where('siswa_id', $s->id)->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->where('status', 'A')->count();
                $totalHari  = $totalHadir + $totalSakit + $totalIzin + $totalAlpa;
                $persentase = $totalHari > 0 ? round(($totalHadir / $totalHari) * 100) : 100;

                $rekapBulanan[$s->id] = [
                    'hadir' => $totalHadir,
                    'sakit' => $totalSakit,
                    'izin'  => $totalIzin,
                    'alpa'  => $totalAlpa,
                    'persen'=> $persentase,
                ];
            }
        }

        $profil = ProfilSekolah::first() ?? new ProfilSekolah();

        return view('guru.absensi', compact(
            'wali',
            'siswaList',
            'absensiHariIni',
            'tanggal',
            'mode',
            'bulan',
            'tahun',
            'rekapBulanan',
            'profil'
        ));
    }

    public function simpan(Request $request)
    {
        $wali = $this->getWaliKelasAuth();
        if (!$wali) {
            abort(403, 'Hanya wali kelas yang boleh mencatat absensi.');
        }

        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $statuses = $request->input('status', []);
        $keterangans = $request->input('keterangan', []);

        foreach ($statuses as $siswaId => $status) {
            Absensi::updateOrCreate(
                [
                    'tanggal' => $tanggal,
                    'siswa_id' => $siswaId,
                    'kelas' => $wali->kode_kelas,
                    'rombel' => $wali->rombel,
                ],
                [
                    'id' => "ABS-{$tanggal}-{$siswaId}",
                    'status' => $status,
                    'keterangan' => $keterangans[$siswaId] ?? null,
                    'dicatat_oleh' => Auth::user()->full_name,
                ]
            );
        }

        return redirect()->route('guru.absensi', ['tanggal' => $tanggal])
            ->with('success', 'Presensi siswa berhasil disimpan ke sistem.');
    }

    /**
     * Cetak Rekap Absensi Bulanan A4 dengan Kop Surat & TTE Elektronik
     */
    public function cetakRekap(Request $request)
    {
        $wali = $this->getWaliKelasAuth();
        if (!$wali) {
            abort(403, 'Hanya wali kelas yang boleh mencetak rekap absensi.');
        }

        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $profil = ProfilSekolah::first() ?? new ProfilSekolah();

        $siswaList = Siswa::where('kelas', $wali->kode_kelas)
            ->where('rombel', $wali->rombel)
            ->orderBy('nama', 'asc')
            ->get();

        $rekapData = [];
        $grandH = 0; $grandS = 0; $grandI = 0; $grandA = 0;

        foreach ($siswaList as $s) {
            $h = Absensi::where('siswa_id', $s->id)->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->where('status', 'H')->count();
            $skt = Absensi::where('siswa_id', $s->id)->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->where('status', 'S')->count();
            $i = Absensi::where('siswa_id', $s->id)->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->where('status', 'I')->count();
            $a = Absensi::where('siswa_id', $s->id)->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->where('status', 'A')->count();
            $total = $h + $skt + $i + $a;
            $pct = $total > 0 ? round(($h / $total) * 100) : 100;

            $grandH += $h; $grandS += $skt; $grandI += $i; $grandA += $a;

            $rekapData[] = [
                'siswa' => $s,
                'h' => $h,
                's' => $skt,
                'i' => $i,
                'a' => $a,
                'persen' => $pct
            ];
        }

        return view('guru.cetak_rekap_absen', compact(
            'wali',
            'profil',
            'bulan',
            'tahun',
            'rekapData',
            'grandH',
            'grandS',
            'grandI',
            'grandA'
        ));
    }
}
