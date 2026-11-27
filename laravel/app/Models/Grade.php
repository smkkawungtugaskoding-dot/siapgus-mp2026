<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table = 'grades';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'student_id',
        'subject_id',
        'classroom_id',
        'tp1',
        'tp2',
        'tp3',
        'tp4',
        'sts',
        'sas',
        'practical_exam',
        'final_score',
        'competency_achievement',
        'semester',
        'academic_year',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }

    /**
     * Hitung Nilai Akhir Otomatis (Kurikulum Merdeka)
     * Menggabungkan TP, STS, SAS, dan Ujian Praktek (0-100)
     */
    public function calculateFinalScore(): float
    {
        $tps = array_filter([$this->tp1, $this->tp2, $this->tp3, $this->tp4], fn($v) => $v > 0);
        $avgTp = count($tps) > 0 ? (array_sum($tps) / count($tps)) : 0;

        $na = ($avgTp * 0.5) + ($this->sts * 0.2) + ($this->sas * 0.15) + ($this->practical_exam * 0.15);
        $this->final_score = round($na, 1);
        return $this->final_score;
    }
}
