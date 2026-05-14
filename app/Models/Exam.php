<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'title',
        'description',
        'duration',
        'total_marks',
        'pass_marks',
        'status',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(StudentExamAttempt::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}