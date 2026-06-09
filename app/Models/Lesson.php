<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'section_id', 'title', 'type', 'content', 'video_url',
        'duration', 'order', 'is_free',
    ];

    protected function casts(): array
    {
        return ['is_free' => 'boolean'];
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
