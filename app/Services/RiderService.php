<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\RiderProfile;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkLog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RiderService
{
    /**
     * Register a new rider with user account and rider profile.
     *
     * Creates the user record with the rider role and a corresponding rider profile
     * in a single database transaction.
     *
     * @param array $userData User account attributes (name, email, password, phone, etc.).
     * @param array $profileData Rider profile attributes (license_number, guarantor info, etc.).
     * @return User The newly created user with the rider profile loaded.
     *
     * @throws \Exception
     */
    public function register(array $userData, array $profileData): User
    {
        try {
            return DB::transaction(function () use ($userData, $profileData) {
                $userData['role'] = 'rider';
                $userData['status'] = 'pending';

                if (isset($userData['password'])) {
                    $userData['password'] = Hash::make($userData['password']);
                }

                $user = User::create($userData);

                $profileData['user_id'] = $user->id;
                RiderProfile::create($profileData);

                $user->load('riderProfile');

                Log::info('Rider registered successfully.', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);

                return $user;
            });
        } catch (\Exception $e) {
            Log::error('Failed to register rider.', [
                'error' => $e->getMessage(),
                'email' => $userData['email'] ?? 'unknown',
            ]);

            throw $e;
        }
    }

    /**
     * Update the rider profile data for a given user.
     *
     * @param User $rider The rider user whose profile should be updated.
     * @param array $data The profile attributes to update.
     * @return void
     *
     * @throws \InvalidArgumentException If the user is not a rider.
     * @throws \Exception
     */
    public function updateProfile(User $rider, array $data): void
    {
        if (! $rider->isRider()) {
            throw new \InvalidArgumentException('The specified user is not a rider.');
        }

        try {
            if ($rider->riderProfile) {
                $rider->riderProfile->update($data);
            } else {
                $data['user_id'] = $rider->id;
                RiderProfile::create($data);
            }

            Log::info('Rider profile updated.', [
                'rider_id' => $rider->id,
                'updated_fields' => array_keys($data),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update rider profile.', [
                'rider_id' => $rider->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Approve a rider account.
     *
     * Marks the rider as verified and sets their status to active.
     * Dispatches a RiderApproved event for downstream listeners.
     *
     * @param User $rider The rider to approve.
     * @return void
     *
     * @throws \InvalidArgumentException If the user is not a rider.
     * @throws \Exception
     */
    public function approve(User $rider): void
    {
        if (! $rider->isRider()) {
            throw new \InvalidArgumentException('The specified user is not a rider.');
        }

        try {
            $rider->update([
                'is_verified' => true,
                'verified_at' => now(),
                'status' => 'active',
            ]);

            // Dispatch event if the event class exists
            $eventClass = 'App\\Events\\RiderApproved';
            if (class_exists($eventClass)) {
                event(new $eventClass($rider));
            }

            Log::info('Rider approved.', ['rider_id' => $rider->id]);
        } catch (\Exception $e) {
            Log::error('Failed to approve rider.', [
                'rider_id' => $rider->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Suspend a rider account with a reason.
     *
     * @param User $rider The rider to suspend.
     * @param string $reason The reason for suspension.
     * @return void
     *
     * @throws \InvalidArgumentException If the user is not a rider.
     * @throws \Exception
     */
    public function suspend(User $rider, string $reason): void
    {
        if (! $rider->isRider()) {
            throw new \InvalidArgumentException('The specified user is not a rider.');
        }

        try {
            $rider->update(['status' => 'suspended']);

            Log::warning('Rider suspended.', [
                'rider_id' => $rider->id,
                'reason' => $reason,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to suspend rider.', [
                'rider_id' => $rider->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Reactivate a suspended rider account.
     *
     * @param User $rider The rider to activate.
     * @return void
     *
     * @throws \InvalidArgumentException If the user is not a rider.
     * @throws \Exception
     */
    public function activate(User $rider): void
    {
        if (! $rider->isRider()) {
            throw new \InvalidArgumentException('The specified user is not a rider.');
        }

        try {
            $rider->update(['status' => 'active']);

            Log::info('Rider activated.', ['rider_id' => $rider->id]);
        } catch (\Exception $e) {
            Log::error('Failed to activate rider.', [
                'rider_id' => $rider->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get the vehicle currently assigned to a rider.
     *
     * @param User $rider The rider to look up.
     * @return Vehicle|null The assigned vehicle, or null if none is assigned.
     */
    public function getAssignedVehicle(User $rider): ?Vehicle
    {
        return Vehicle::where('assigned_rider_id', $rider->id)->first();
    }

    /**
     * Get a payment summary for a rider.
     *
     * Calculates total paid, total due, arrears, and next due date.
     *
     * @param User $rider The rider to summarize payments for.
     * @return array{total_paid: float, total_due: float, arrears: float, next_due_date: string|null}
     */
    public function getPaymentSummary(User $rider): array
    {
        $totalPaid = Payment::where('rider_id', $rider->id)
            ->where('status', 'completed')
            ->sum('amount');

        $totalDue = Payment::where('rider_id', $rider->id)
            ->where('status', 'pending')
            ->sum('amount');

        $arrears = Payment::where('rider_id', $rider->id)
            ->where('status', 'pending')
            ->where('due_date', '<', Carbon::today())
            ->sum('amount');

        $nextDueDate = Payment::where('rider_id', $rider->id)
            ->where('status', 'pending')
            ->where('due_date', '>=', Carbon::today())
            ->orderBy('due_date', 'asc')
            ->value('due_date');

        return [
            'total_paid' => (float) $totalPaid,
            'total_due' => (float) $totalDue,
            'arrears' => (float) $arrears,
            'next_due_date' => $nextDueDate?->toDateString(),
        ];
    }

    /**
     * Get aggregate rider statistics.
     *
     * Returns counts of total riders and breakdowns by status.
     *
     * @return array{total: int, active: int, suspended: int, pending: int, inactive: int}
     */
    public function getRiderStats(): array
    {
        $counts = User::where('role', 'rider')
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = 'suspended' THEN 1 ELSE 0 END) as suspended,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive
            ")
            ->first();

        return [
            'total' => (int) $counts->total,
            'active' => (int) $counts->active,
            'suspended' => (int) $counts->suspended,
            'pending' => (int) $counts->pending,
            'inactive' => (int) $counts->inactive,
        ];
    }

    /**
     * Clock in a rider, creating an open work log entry.
     *
     * Records the clock-in time and GPS coordinates. If the rider has an assigned vehicle,
     * it is linked to the work log.
     *
     * @param User $rider The rider to clock in.
     * @param float $lat The latitude at clock-in.
     * @param float $lng The longitude at clock-in.
     * @return WorkLog The newly created work log.
     *
     * @throws \RuntimeException If the rider is already clocked in.
     * @throws \Exception
     */
    public function clockIn(User $rider, float $lat, float $lng): WorkLog
    {
        // Check for an already-open work log
        $existingLog = WorkLog::where('rider_id', $rider->id)
            ->whereNull('clock_out_at')
            ->first();

        if ($existingLog) {
            throw new \RuntimeException('Rider is already clocked in.');
        }

        try {
            $vehicle = $this->getAssignedVehicle($rider);

            $workLog = WorkLog::create([
                'rider_id' => $rider->id,
                'vehicle_id' => $vehicle?->id,
                'clock_in_at' => now(),
                'clock_in_latitude' => $lat,
                'clock_in_longitude' => $lng,
            ]);

            Log::info('Rider clocked in.', [
                'rider_id' => $rider->id,
                'work_log_id' => $workLog->id,
            ]);

            return $workLog;
        } catch (\RuntimeException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to clock in rider.', [
                'rider_id' => $rider->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Clock out a rider, completing the open work log entry.
     *
     * Records the clock-out time, GPS coordinates, and calculates total hours worked.
     *
     * @param User $rider The rider to clock out.
     * @param float $lat The latitude at clock-out.
     * @param float $lng The longitude at clock-out.
     * @return WorkLog The updated work log.
     *
     * @throws \RuntimeException If no active work log exists for the rider.
     * @throws \Exception
     */
    public function clockOut(User $rider, float $lat, float $lng): WorkLog
    {
        $workLog = WorkLog::where('rider_id', $rider->id)
            ->whereNull('clock_out_at')
            ->latest('clock_in_at')
            ->first();

        if (! $workLog) {
            throw new \RuntimeException('No active clock-in found for this rider.');
        }

        try {
            $clockOutTime = now();
            $totalHours = $workLog->clock_in_at
                ? round($workLog->clock_in_at->diffInMinutes($clockOutTime) / 60, 2)
                : 0;

            $workLog->update([
                'clock_out_at' => $clockOutTime,
                'clock_out_latitude' => $lat,
                'clock_out_longitude' => $lng,
                'total_hours' => $totalHours,
            ]);

            $workLog->refresh();

            Log::info('Rider clocked out.', [
                'rider_id' => $rider->id,
                'work_log_id' => $workLog->id,
                'total_hours' => $totalHours,
            ]);

            return $workLog;
        } catch (\RuntimeException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to clock out rider.', [
                'rider_id' => $rider->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
