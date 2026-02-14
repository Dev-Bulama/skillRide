<?php

namespace App\Services;

use App\Models\FraudAlert;
use App\Models\GpsLocation;
use App\Models\Payment;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Collection;

class FraudDetectionService
{
    public function checkDuplicatePayment(Payment $payment): bool
    {
        $duplicate = Payment::where('rider_id', $payment->rider_id)
            ->where('amount', $payment->amount)
            ->where('id', '!=', $payment->id)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->exists();

        if ($duplicate) {
            $this->createAlert(
                'duplicate_payment',
                'high',
                "Possible duplicate payment of ₦{$payment->amount} by rider #{$payment->rider_id}",
                User::find($payment->rider_id),
                null,
                ['payment_id' => $payment->id, 'amount' => $payment->amount]
            );
        }

        return $duplicate;
    }

    public function checkLocationAnomaly(GpsLocation $location, Vehicle $vehicle): bool
    {
        $lastLocation = GpsLocation::where('vehicle_id', $vehicle->id)
            ->where('id', '!=', $location->id)
            ->orderByDesc('recorded_at')
            ->first();

        if (!$lastLocation) {
            return false;
        }

        $timeDiff = $location->recorded_at->diffInSeconds($lastLocation->recorded_at);
        if ($timeDiff === 0) {
            return false;
        }

        $distance = $this->haversineDistance(
            $lastLocation->latitude,
            $lastLocation->longitude,
            $location->latitude,
            $location->longitude
        );

        $speedKmh = ($distance / $timeDiff) * 3600;

        if ($speedKmh > 200) {
            $this->createAlert(
                'location_anomaly',
                'medium',
                "Vehicle {$vehicle->registration_number} detected at impossible speed ({$speedKmh} km/h)",
                $vehicle->assignedRider,
                $vehicle,
                ['speed' => $speedKmh, 'distance' => $distance, 'time_diff' => $timeDiff]
            );
            return true;
        }

        return false;
    }

    public function checkLoginAnomaly(User $user, string $ip): bool
    {
        if ($user->last_login_ip && $user->last_login_ip !== $ip) {
            $recentLogins = \DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('ip_address', '!=', $ip)
                ->where('last_activity', '>=', now()->subHour()->timestamp)
                ->count();

            if ($recentLogins > 0) {
                $this->createAlert(
                    'login_anomaly',
                    'low',
                    "User {$user->name} logged in from new IP {$ip}",
                    $user,
                    null,
                    ['ip' => $ip, 'previous_ip' => $user->last_login_ip]
                );
                return true;
            }
        }

        return false;
    }

    public function createAlert(
        string $type,
        string $severity,
        string $description,
        ?User $user,
        ?Vehicle $vehicle,
        ?array $data = null
    ): FraudAlert {
        return FraudAlert::create([
            'user_id' => $user?->id,
            'vehicle_id' => $vehicle?->id,
            'type' => $type,
            'severity' => $severity,
            'description' => $description,
            'data' => $data,
            'status' => 'open',
        ]);
    }

    public function getOpenAlerts(): Collection
    {
        return FraudAlert::whereIn('status', ['open', 'investigating'])
            ->with(['user', 'vehicle'])
            ->orderByRaw("CASE severity WHEN 'critical' THEN 1 WHEN 'high' THEN 2 WHEN 'medium' THEN 3 WHEN 'low' THEN 4 END")
            ->orderByDesc('created_at')
            ->get();
    }

    protected function haversineDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}
