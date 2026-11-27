<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyJournal extends Model
{
    protected $table = 'daily_journals';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'date',
        'teacher_id',
        'subject_id',
        'classroom_id',
        'teaching_hours',
        'topic',
        'attendance_summary',
        'reflection_notes',
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
