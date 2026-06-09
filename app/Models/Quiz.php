<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['title', 'course_id', 'passing_score', 'time_limit', 'attempts', 'description'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function userAttempts(int $userId)
    {
        return $this->hasMany(QuizAttempt::class)->where('user_id', $userId);
    }

    public function getQuestionCountAttribute(): int
    {
        return $this->questions()->count();
    }
}
