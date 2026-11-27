<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianRapor extends Model
{
    protected $table = 'penilaian_rapor';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'siswa_id',
        'mapel_id',
        'kelas',
        'rombel',
        'tp1',
        'tp2',
        'tp3',
        'tp4',
        'sts',
        'sas',
        'ujian_praktek',
        'nilai_akhir',
        'capaian',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id');
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id', 'id');
    }

    // Hitung Nilai Akhir Otomatis (Kurikulum Merdeka)
    public function hitungNilaiAkhir()
    {
        $tps = array_filter([$this->tp1, $this->tp2, $this->tp3, $this->tp4], fn($v) => $v > 0);
        $avgTp = count($tps) > 0 ? (array_sum($tps) / count($tps)) : 0;
        
        // Bobot: 50% TP, 20% STS, 15% SAS, 15% Ujian Praktek
        $na = ($avgTp * 0.5) + ($this->sts * 0.2) + ($this->sas * 0.15) + ($this->ujian_praktek * 0.15);
        $this->nilai_akhir = round($na, 1);
        return $this->nilai_akhir;
    }
}
