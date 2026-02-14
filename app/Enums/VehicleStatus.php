<?php

namespace App\Enums;

enum VehicleStatus: string
{
    case Active = 'active';
    case Maintenance = 'maintenance';
    case Inactive = 'inactive';
    case Decommissioned = 'decommissioned';

    public function label(): string
    {
        return match($this) {
            self::Active => 'Active',
            self::Maintenance => 'In Maintenance',
            self::Inactive => 'Inactive',
            self::Decommissioned => 'Decommissioned',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Active => 'green',
            self::Maintenance => 'yellow',
            self::Inactive => 'gray',
            self::Decommissioned => 'red',
        };
    }
}
