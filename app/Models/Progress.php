<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    protected $table = 'progress';

    protected $fillable = ['user_id', 'lesson_id', 'completed', 'watched_sec'];

    protected function casts(): array
    {
        return ['completed' => 'boolean'];
    }
}
