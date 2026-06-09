<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'status', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }

    public function getProgressPercentAttribute(): int
    {
        if (!$this->relationLoaded('course') || !$this->course->relationLoaded('sections')) {
            return 0;
        }
        $total = $this->course->sections->sum(fn($s) => $s->lessons->count());
        if ($total === 0) return 0;
        $done = \App\Models\Progress::where('user_id', $this->user_id)
            ->whereIn('lesson_id', $this->course->sections->flatMap(fn($s) => $s->lessons->pluck('id')))
            ->where('completed', true)
            ->count();
        return (int) round(($done / $total) * 100);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
