<?php

namespace App\Enums;

enum VehicleType: string
{
    case KekeNapep = 'keke_napep';
    case Car = 'car';
    case Bus = 'bus';
    case Truck = 'truck';

    public function label(): string
    {
        return match($this) {
            self::KekeNapep => 'Keke Napep',
            self::Car => 'Car',
            self::Bus => 'Bus',
            self::Truck => 'Truck',
        };
    }
}
