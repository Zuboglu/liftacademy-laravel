<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Simulation extends Model
{
    protected $fillable = ['title', 'course_id', 'scenario', 'difficulty'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
