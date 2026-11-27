<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'classroom_id',
        'nis',
        'nisn',
        'name',
        'gender',
        'phone',
        'parent_name',
        'address',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'student_id', 'id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'student_id', 'id');
    }
}
