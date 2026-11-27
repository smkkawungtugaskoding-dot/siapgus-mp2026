<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'date',
        'student_id',
        'classroom_id',
        'status',
        'notes',
        'recorded_by',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }
}
