<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'user_id',
        'exam_id',
        'attempt_id',
        'total_marks',
        'obtained_marks',
        'percentage',
        'status',
        'is_graded',
        'manual_marks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function attempt()
    {
        return $this->belongsTo(StudentExamAttempt::class, 'attempt_id');
    }
}