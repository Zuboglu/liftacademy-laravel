<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = [
        'user_id', 'quiz_id', 'score', 'passed',
        'answers', 'correct_count', 'total_questions',
        'started_at', 'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'answers'     => 'array',
            'passed'      => 'boolean',
            'started_at'  => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
