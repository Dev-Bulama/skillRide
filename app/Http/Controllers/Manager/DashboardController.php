<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the manager dashboard with summary statistics.
     *
     * Shows assigned riders count, active vehicles, recent payments
     * from riders, and relevant alerts for the manager's fleet.
     */
    public function index(): View
    {
        $manager = Auth::user();

        abort_unless($manager->isManager(), 403, 'Unauthorized. Manager access required.');

        // Get all vehicles managed by this manager
        $managedVehicles = Vehicle::where('assigned_manager_id', $manager->id)
            ->with(['assignedRider', 'maintenanceRecords'])
            ->get();

        $managedVehicleIds = $managedVehicles->pluck('id');
        $assignedRiderIds = $managedVehicles->pluck('assigned_rider_id')->filter()->unique();

        // Compute dashboard statistics
        $assignedRidersCount = $assignedRiderIds->count();
        $activeVehiclesCount = $managedVehicles->where('status', 'active')->count();
        $maintenanceVehiclesCount = $managedVehicles->where('status', 'maintenance')->count();
        $totalVehiclesCount = $managedVehicles->count();

        // Recent payments from assigned riders
        $recentPayments = Payment::whereIn('rider_id', $assignedRiderIds)
            ->with(['rider', 'paymentPlan'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Payment summary stats
        $totalPaymentsThisMonth = Payment::whereIn('rider_id', $assignedRiderIds)
            ->where('status', 'completed')
            ->whereMonth('paid_at', Carbon::now()->month)
            ->whereYear('paid_at', Carbon::now()->year)
            ->sum('amount');

        $overduePaymentsCount = Payment::whereIn('rider_id', $assignedRiderIds)
            ->where('status', 'pending')
            ->where('due_date', '<', Carbon::today())
            ->count();

        // Build alerts
        $alerts = collect();

        // Overdue payment alerts
        if ($overduePaymentsCount > 0) {
            $alerts->push([
                'type' => 'warning',
                'message' => "{$overduePaymentsCount} rider(s) have overdue payments.",
                'action_url' => route('manager.payments.index', ['filter' => 'overdue']),
            ]);
        }

        // Vehicles in maintenance alerts
        if ($maintenanceVehiclesCount > 0) {
            $alerts->push([
                'type' => 'info',
                'message' => "{$maintenanceVehiclesCount} vehicle(s) currently in maintenance.",
                'action_url' => route('manager.maintenance.index'),
            ]);
        }

        // Insurance/road worthiness expiry alerts
        $expiringVehicles = $managedVehicles->filter(function ($vehicle) {
            $threshold = Carbon::now()->addDays(30);

            return ($vehicle->insurance_expiry && $vehicle->insurance_expiry->lte($threshold))
                || ($vehicle->road_worthiness_expiry && $vehicle->road_worthiness_expiry->lte($threshold));
        });

        if ($expiringVehicles->isNotEmpty()) {
            $alerts->push([
                'type' => 'danger',
                'message' => $expiringVehicles->count() . ' vehicle(s) have expiring insurance or road worthiness.',
                'action_url' => route('manager.maintenance.schedules'),
            ]);
        }

        // Unassigned vehicles (no rider)
        $unassignedCount = $managedVehicles->whereNull('assigned_rider_id')->count();
        if ($unassignedCount > 0) {
            $alerts->push([
                'type' => 'info',
                'message' => "{$unassignedCount} vehicle(s) have no assigned rider.",
                'action_url' => route('manager.riders.index'),
            ]);
        }

        return view('manager.dashboard.index', compact(
            'manager',
            'assignedRidersCount',
            'activeVehiclesCount',
            'maintenanceVehiclesCount',
            'totalVehiclesCount',
            'recentPayments',
            'totalPaymentsThisMonth',
            'overduePaymentsCount',
            'alerts',
            'managedVehicles',
        ));
    }
}
