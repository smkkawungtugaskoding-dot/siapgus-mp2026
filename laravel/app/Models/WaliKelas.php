<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaliKelas extends Model
{
    protected $table = 'wali_kelas';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'guru_id',
        'nama_guru',
        'kode_kelas',
        'rombel',
        'tahun_ajaran',
        'semester',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'id');
    }
}
