<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nik_nuptk',
        'nama',
        'jenis_kelamin',
        'email',
        'no_hp',
    ];

    public function waliKelas()
    {
        return $this->hasMany(WaliKelas::class, 'guru_id', 'id');
    }
}
