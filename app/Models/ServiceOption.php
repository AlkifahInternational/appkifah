<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_service_id',
        'name_en',
        'name_ar',
        'unit_label_en',
        'unit_label_ar',
        'base_price',
        'urgent_multiplier',
        'min_quantity',
        'max_quantity',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'urgent_multiplier' => 'decimal:2',
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function subService(): BelongsTo
    {
        return $this->belongsTo(SubService::class);
    }

    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function getUnitLabelAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->unit_label_ar : $this->unit_label_en;
    }

    public function getPriceForUrgency(bool $isUrgent): float
    {
        return $isUrgent
            ? $this->base_price * $this->urgent_multiplier
            : $this->base_price;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
