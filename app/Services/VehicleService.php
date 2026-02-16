<?php

namespace App\Services;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VehicleService
{
    /**
     * Create a new vehicle record.
     *
     * @param array $data The vehicle attributes.
     * @return Vehicle
     *
     * @throws \Exception
     */
    public function create(array $data): Vehicle
    {
        try {
            return DB::transaction(function () use ($data) {
                $vehicle = Vehicle::create($data);

                Log::info('Vehicle created.', [
                    'vehicle_id' => $vehicle->id,
                    'registration_number' => $vehicle->registration_number,
                ]);

                return $vehicle;
            });
        } catch (\Exception $e) {
            Log::error('Failed to create vehicle.', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);

            throw $e;
        }
    }

    /**
     * Update an existing vehicle record.
     *
     * @param Vehicle $vehicle The vehicle to update.
     * @param array $data The attributes to update.
     * @return Vehicle
     *
     * @throws \Exception
     */
    public function update(Vehicle $vehicle, array $data): Vehicle
    {
        try {
            $vehicle->update($data);
            $vehicle->refresh();

            Log::info('Vehicle updated.', [
                'vehicle_id' => $vehicle->id,
                'updated_fields' => array_keys($data),
            ]);

            return $vehicle;
        } catch (\Exception $e) {
            Log::error('Failed to update vehicle.', [
                'vehicle_id' => $vehicle->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Assign a rider to a vehicle.
     *
     * Unassigns the rider from any previously assigned vehicle before making the new assignment.
     *
     * @param Vehicle $vehicle The vehicle to assign the rider to.
     * @param User $rider The rider user to assign.
     * @return void
     *
     * @throws \InvalidArgumentException If the user is not a rider.
     * @throws \Exception
     */
    public function assignRider(Vehicle $vehicle, User $rider): void
    {
        if (! $rider->isRider()) {
            throw new \InvalidArgumentException('The specified user is not a rider.');
        }

        try {
            DB::transaction(function () use ($vehicle, $rider) {
                // Unassign rider from any previously assigned vehicle
                Vehicle::where('assigned_rider_id', $rider->id)
                    ->where('id', '!=', $vehicle->id)
                    ->update(['assigned_rider_id' => null]);

                $vehicle->update(['assigned_rider_id' => $rider->id]);

                // Update rider profile with vehicle assignment
                if ($rider->riderProfile) {
                    $rider->riderProfile->update(['vehicle_id' => $vehicle->id]);
                }

                Log::info('Rider assigned to vehicle.', [
                    'vehicle_id' => $vehicle->id,
                    'rider_id' => $rider->id,
                ]);
            });
        } catch (\InvalidArgumentException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to assign rider to vehicle.', [
                'vehicle_id' => $vehicle->id,
                'rider_id' => $rider->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Assign a manager to a vehicle.
     *
     * @param Vehicle $vehicle The vehicle to assign the manager to.
     * @param User $manager The manager user to assign.
     * @return void
     *
     * @throws \InvalidArgumentException If the user is not a manager.
     * @throws \Exception
     */
    public function assignManager(Vehicle $vehicle, User $manager): void
    {
        if (! $manager->isManager() && ! $manager->isAdmin()) {
            throw new \InvalidArgumentException('The specified user is not a manager or admin.');
        }

        try {
            $vehicle->update(['assigned_manager_id' => $manager->id]);

            Log::info('Manager assigned to vehicle.', [
                'vehicle_id' => $vehicle->id,
                'manager_id' => $manager->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to assign manager to vehicle.', [
                'vehicle_id' => $vehicle->id,
                'manager_id' => $manager->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Unassign the current rider from a vehicle.
     *
     * Clears both the vehicle's rider assignment and the rider profile's vehicle reference.
     *
     * @param Vehicle $vehicle The vehicle to unassign the rider from.
     * @return void
     *
     * @throws \Exception
     */
    public function unassignRider(Vehicle $vehicle): void
    {
        try {
            DB::transaction(function () use ($vehicle) {
                $riderId = $vehicle->assigned_rider_id;

                $vehicle->update(['assigned_rider_id' => null]);

                // Clear vehicle assignment in rider profile
                if ($riderId) {
                    $rider = User::find($riderId);
                    if ($rider && $rider->riderProfile) {
                        $rider->riderProfile->update(['vehicle_id' => null]);
                    }
                }

                Log::info('Rider unassigned from vehicle.', [
                    'vehicle_id' => $vehicle->id,
                    'previous_rider_id' => $riderId,
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Failed to unassign rider from vehicle.', [
                'vehicle_id' => $vehicle->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Update the current GPS location of a vehicle.
     *
     * @param Vehicle $vehicle The vehicle to update.
     * @param float $lat The latitude coordinate.
     * @param float $lng The longitude coordinate.
     * @return void
     *
     * @throws \Exception
     */
    public function updateLocation(Vehicle $vehicle, float $lat, float $lng): void
    {
        try {
            $vehicle->update([
                'current_latitude' => $lat,
                'current_longitude' => $lng,
                'last_location_update' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update vehicle location.', [
                'vehicle_id' => $vehicle->id,
                'latitude' => $lat,
                'longitude' => $lng,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get all vehicles with an active status.
     *
     * @return Collection<int, Vehicle>
     */
    public function getActiveVehicles(): Collection
    {
        return Vehicle::active()
            ->with(['assignedRider', 'assignedManager'])
            ->get();
    }

    /**
     * Get all vehicles matching a given status.
     *
     * @param string $status The status to filter by (active, maintenance, inactive, decommissioned).
     * @return Collection<int, Vehicle>
     */
    public function getVehiclesByStatus(string $status): Collection
    {
        return Vehicle::where('status', $status)
            ->with(['assignedRider', 'assignedManager'])
            ->get();
    }

    /**
     * Get aggregate vehicle statistics.
     *
     * Returns counts of total vehicles and breakdowns by status.
     *
     * @return array{total: int, active: int, maintenance: int, inactive: int, decommissioned: int}
     */
    public function getVehicleStats(): array
    {
        $counts = Vehicle::query()
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = 'maintenance' THEN 1 ELSE 0 END) as maintenance,
                SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive,
                SUM(CASE WHEN status = 'decommissioned' THEN 1 ELSE 0 END) as decommissioned
            ")
            ->first();

        return [
            'total' => (int) $counts->total,
            'active' => (int) $counts->active,
            'maintenance' => (int) $counts->maintenance,
            'inactive' => (int) $counts->inactive,
            'decommissioned' => (int) $counts->decommissioned,
        ];
    }

    /**
     * Generate a QR code image for a vehicle and store it on disk.
     *
     * The QR code encodes a JSON payload containing the vehicle's ID and registration number.
     * The generated SVG file is saved to the public disk under the qr-codes directory.
     *
     * @param Vehicle $vehicle The vehicle to generate a QR code for.
     * @return string The relative storage path to the generated QR code image.
     *
     * @throws \Exception
     */
    public function generateQrCode(Vehicle $vehicle): string
    {
        try {
            $data = json_encode([
                'vehicle_id' => $vehicle->id,
                'registration_number' => $vehicle->registration_number,
                'type' => $vehicle->type,
            ]);

            $filename = 'qr-codes/vehicle-' . $vehicle->id . '.svg';
            $storagePath = storage_path('app/public/' . $filename);

            // Ensure the directory exists
            $directory = dirname($storagePath);
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $qrCode = QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate($data);

            file_put_contents($storagePath, $qrCode);

            $vehicle->update(['qr_code_path' => $filename]);

            Log::info('QR code generated for vehicle.', [
                'vehicle_id' => $vehicle->id,
                'path' => $filename,
            ]);

            return $filename;
        } catch (\Exception $e) {
            Log::error('Failed to generate QR code for vehicle.', [
                'vehicle_id' => $vehicle->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
