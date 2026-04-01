<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicianProfile extends Model
{
    protected $fillable = [
        'user_id',
        'bio_en',
        'bio_ar',
        'certifications',
        'specializations',
        'is_available',
        'is_verified',
        'rating',
        'total_jobs',
        'completed_jobs',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'certifications' => 'array',
        'specializations' => 'array',
        'is_available' => 'boolean',
        'is_verified' => 'boolean',
        'rating' => 'decimal:2',
        'total_jobs' => 'integer',
        'completed_jobs' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getBioAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->bio_ar : $this->bio_en;
    }

    public function getCompletionRateAttribute(): float
    {
        return $this->total_jobs > 0
            ? round(($this->completed_jobs / $this->total_jobs) * 100, 1)
            : 0;
    }
}
