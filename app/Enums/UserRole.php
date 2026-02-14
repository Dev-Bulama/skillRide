<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Manager = 'manager';
    case Rider = 'rider';

    public function label(): string
    {
        return match($this) {
            self::Admin => 'Administrator',
            self::Manager => 'Fleet Manager',
            self::Rider => 'Rider',
        };
    }
}
