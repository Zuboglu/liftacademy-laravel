<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'cert_number', 'level', 'status',
        'recipient_name', 'instructor_name', 'training_hours',
        'completed_at', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'expires_at'   => 'datetime',
            'created_at'   => 'datetime',
        ];
    }

    public function getIssuedAtAttribute()
    {
        return $this->created_at;
    }

    public function getCertificateNumberAttribute()
    {
        return $this->cert_number;
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
