<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case TECHNICAL_MANAGER = 'technical_manager';
    case TECHNICIAN = 'technician';
    case CLIENT = 'client';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => __('Super Admin'),
            self::TECHNICAL_MANAGER => __('Technical Manager'),
            self::TECHNICIAN => __('Technician'),
            self::CLIENT => __('Client'),
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'shield-check',
            self::TECHNICAL_MANAGER => 'cog',
            self::TECHNICIAN => 'wrench',
            self::CLIENT => 'user',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'purple',
            self::TECHNICAL_MANAGER => 'blue',
            self::TECHNICIAN => 'green',
            self::CLIENT => 'gray',
        };
    }
}
