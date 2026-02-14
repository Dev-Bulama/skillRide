<?php

namespace App\Services;

use App\Models\GpsLocation;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class GpsTrackingService
{
    public function recordLocation(int $vehicleId, int $riderId, array $coords): GpsLocation
    {
        $location = GpsLocation::create([
            'vehicle_id' => $vehicleId,
            'rider_id' => $riderId,
            'latitude' => $coords['latitude'],
            'longitude' => $coords['longitude'],
            'speed' => $coords['speed'] ?? null,
            'heading' => $coords['heading'] ?? null,
            'altitude' => $coords['altitude'] ?? null,
            'accuracy' => $coords['accuracy'] ?? null,
            'recorded_at' => now(),
        ]);

        Vehicle::where('id', $vehicleId)->update([
            'current_latitude' => $coords['latitude'],
            'current_longitude' => $coords['longitude'],
            'last_location_update' => now(),
        ]);

        return $location;
    }

    public function getLatestLocation(int $vehicleId): ?GpsLocation
    {
        return GpsLocation::where('vehicle_id', $vehicleId)
            ->orderByDesc('recorded_at')
            ->first();
    }

    public function getVehicleTrail(int $vehicleId, Carbon $from, Carbon $to): Collection
    {
        return GpsLocation::where('vehicle_id', $vehicleId)
            ->whereBetween('recorded_at', [$from, $to])
            ->orderBy('recorded_at')
            ->get();
    }

    public function getAllActiveLocations(): Collection
    {
        return Vehicle::where('status', 'active')
            ->whereNotNull('current_latitude')
            ->whereNotNull('current_longitude')
            ->with('assignedRider:id,name,phone')
            ->select('id', 'registration_number', 'type', 'current_latitude', 'current_longitude', 'last_location_update', 'assigned_rider_id')
            ->get();
    }

    public function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }
}
