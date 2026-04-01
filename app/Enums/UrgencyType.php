<?php

namespace App\Enums;

enum UrgencyType: string
{
    case URGENT = 'urgent';
    case SCHEDULED = 'scheduled';

    public function label(): string
    {
        return match ($this) {
            self::URGENT => __('Urgent (Now)'),
            self::SCHEDULED => __('Scheduled (Later)'),
        };
    }
}
