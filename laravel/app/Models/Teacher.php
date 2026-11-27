<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'nik_nuptk',
        'name',
        'gender',
        'email',
        'phone',
        'position',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function homeroomAssignments()
    {
        return $this->hasMany(HomeroomAssignment::class, 'teacher_id', 'id');
    }

    public function subjectAssignments()
    {
        return $this->hasMany(TeacherSubjectAssignment::class, 'teacher_id', 'id');
    }
}
