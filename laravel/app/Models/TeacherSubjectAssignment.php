<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSubjectAssignment extends Model
{
    protected $table = 'teacher_subject_assignments';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'teacher_id',
        'subject_id',
        'classroom_id',
        'academic_year',
        'semester',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }
}
