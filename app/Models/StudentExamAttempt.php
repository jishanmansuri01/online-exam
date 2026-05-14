<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentExamAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'exam_id',
        'started_at',
        'submitted_at',
        'status',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function answers()
    {
        return $this->hasMany(StudentAnswer::class, 'attempt_id');
    }

    public function result()
    {
        return $this->hasOne(Result::class, 'attempt_id');
    }
}