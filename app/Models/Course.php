<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'thumbnail',
        'level', 'category', 'crane_type', 'published',
        'price', 'is_mandatory', 'passing_score',
        'instructor_id',
    ];

    protected function casts(): array
    {
        return [
            'is_mandatory' => 'boolean',
            'price'        => 'decimal:2',
        ];
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('order');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function quizzes()
    {
        return $this->hasMany(\App\Models\Quiz::class);
    }

    public function simulations()
    {
        return $this->hasMany(\App\Models\Simulation::class);
    }
}
