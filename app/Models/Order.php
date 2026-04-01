<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\UrgencyType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'client_id',
        'technician_id',
        'assigned_by',
        'status',
        'urgency',
        'scheduled_at',
        'subtotal',
        'tax_amount',
        'total',
        'payment_method',
        'payment_status',
        'address',
        'latitude',
        'longitude',
        'notes',
        'client_notes',
        'started_at',
        'completed_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'urgency' => UrgencyType::class,
            'payment_method' => PaymentMethod::class,
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'subtotal' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    // ── Relationships ─────────────────────────────────

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function assignedByManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    // ── Helpers ─────────────────────────────────

    public static function generateOrderNumber(): string
    {
        $year = date('Y');
        $latest = static::whereYear('created_at', $year)->max('id') ?? 0;
        return sprintf('KIF-%s-%05d', $year, $latest + 1);
    }

    public function isUrgent(): bool
    {
        return $this->urgency === UrgencyType::URGENT;
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    public function scopePending($query)
    {
        return $query->where('status', OrderStatus::PENDING);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            OrderStatus::CONFIRMED,
            OrderStatus::ASSIGNED,
            OrderStatus::EN_ROUTE,
            OrderStatus::IN_PROGRESS,
        ]);
    }

    public function scopeUrgent($query)
    {
        return $query->where('urgency', UrgencyType::URGENT);
    }
}
