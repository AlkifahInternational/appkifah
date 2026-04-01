<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case MADA = 'mada';
    case APPLE_PAY = 'apple_pay';
    case STC_PAY = 'stc_pay';
    case CASH = 'cash';

    public function label(): string
    {
        return match ($this) {
            self::MADA => __('Mada'),
            self::APPLE_PAY => __('Apple Pay'),
            self::STC_PAY => __('STC Pay'),
            self::CASH => __('Cash on Delivery'),
        };
    }

    public function isOnline(): bool
    {
        return in_array($this, [self::MADA, self::APPLE_PAY, self::STC_PAY]);
    }
}
