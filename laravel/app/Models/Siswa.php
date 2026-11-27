<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nisn',
        'nama',
        'kelas',
        'rombel',
        'jenis_kelamin',
        'no_hp',
        'nama_wali',
    ];

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'siswa_id', 'id');
    }

    public function penilaian()
    {
        return $this->hasMany(PenilaianRapor::class, 'siswa_id', 'id');
    }
}
