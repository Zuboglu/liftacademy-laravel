<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'cert_number', 'level', 'status',
        'recipient_name', 'instructor_name', 'training_hours',
        'department', 'site', 'employee_id', 'notes',
        'completed_at', 'issued_at', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'issued_at'    => 'datetime',
            'expires_at'   => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'ACTIVE'  => '#CCFF00',
            'EXPIRED' => '#FF2D2D',
            'REVOKED' => '#888888',
            default   => '#888888',
        };
    }
}
