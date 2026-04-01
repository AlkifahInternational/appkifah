<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case ASSIGNED = 'assigned';
    case EN_ROUTE = 'en_route';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => __('Pending'),
            self::CONFIRMED => __('Confirmed'),
            self::ASSIGNED => __('Assigned'),
            self::EN_ROUTE => __('En Route'),
            self::IN_PROGRESS => __('In Progress'),
            self::COMPLETED => __('Completed'),
            self::CANCELLED => __('Cancelled'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::CONFIRMED => 'blue',
            self::ASSIGNED => 'indigo',
            self::EN_ROUTE => 'cyan',
            self::IN_PROGRESS => 'orange',
            self::COMPLETED => 'green',
            self::CANCELLED => 'red',
        };
    }

    public function isActive(): bool
    {
        return in_array($this, [
            self::CONFIRMED,
            self::ASSIGNED,
            self::EN_ROUTE,
            self::IN_PROGRESS,
        ]);
    }
}
