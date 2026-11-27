<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $table = 'classrooms';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'grade_level',
        'rombel',
        'name',
        'phase',
        'academic_year',
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'classroom_id', 'id');
    }

    public function homeroomAssignment()
    {
        return $this->hasOne(HomeroomAssignment::class, 'classroom_id', 'id');
    }
}
