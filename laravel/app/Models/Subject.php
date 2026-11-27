<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'code',
        'name',
        'kktp',
        'category',
        'phase',
        'hours_per_week',
    ];

    public function grades()
    {
        return $this->hasMany(Grade::class, 'subject_id', 'id');
    }

    public function teacherAssignments()
    {
        return $this->hasMany(TeacherSubjectAssignment::class, 'subject_id', 'id');
    }
}
