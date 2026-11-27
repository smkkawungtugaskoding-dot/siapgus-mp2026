<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilSekolah extends Model
{
    protected $table = 'profil_sekolah';

    protected $fillable = [
        'nama_sekolah',
        'npsn',
        'alamat_sekolah',
        'telp',
        'kepala_nama',
        'kepala_nip',
        'tahun_ajaran',
        'semester',
        'kurikulum',
        'logo_url',
    ];
}
