<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'section_id', 'title', 'type', 'content', 'video_url',
        'video_path', 'video_disk', 'duration', 'order', 'is_free',
    ];

    protected function casts(): array
    {
        return ['is_free' => 'boolean'];
    }

    public function getVideoSrcAttribute(): ?string
    {
        if ($this->video_path) {
            return asset('storage/' . $this->video_path);
        }
        return $this->video_url;
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
