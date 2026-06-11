<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateConfig extends Model
{
    protected $table    = 'certificate_configs';
    protected $fillable = [
        'course_id','cert_level','completion_days',
        'validity_days','requires_quiz','min_watch_pct','notes',
    ];

    protected function casts(): array
    {
        return ['requires_quiz' => 'boolean'];
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function prerequisites()
    {
        return $this->belongsToMany(Course::class, 'cert_prerequisites', 'config_id', 'course_id');
    }
}
