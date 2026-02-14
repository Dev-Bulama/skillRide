<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RiderController extends Controller
{
    /**
     * List all riders assigned to the manager's vehicles.
     *
     * Supports search by name/email and filtering by payment status.
     */
    public function index(Request $request): View
    {
        $manager = Auth::user();

        abort_unless($manager->isManager(), 403, 'Unauthorized. Manager access required.');

        $managedVehicles = Vehicle::where('assigned_manager_id', $manager->id)->get();
        $assignedRiderIds = $managedVehicles->pluck('assigned_rider_id')->filter()->unique()->values();

        $query = User::whereIn('id', $assignedRiderIds)
            ->with(['riderProfile', 'assignedVehicle', 'payments']);

        // Search by name, email, or phone
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by payment status
        if ($filter = $request->input('filter')) {
            switch ($filter) {
                case 'overdue':
                    $query->whereHas('payments', function ($q) {
                        $q->where('status', 'pending')
                          ->where('due_date', '<', Carbon::today());
                    });
                    break;

                case 'paid':
                    $query->whereHas('payments', function ($q) {
                        $q->where('status', 'completed')
                          ->whereMonth('paid_at', Carbon::now()->month)
                          ->whereYear('paid_at', Carbon::now()->year);
                    });
                    break;

                case 'pending':
                    $query->whereHas('payments', function ($q) {
                        $q->where('status', 'pending')
                          ->where('due_date', '>=', Carbon::today());
                    });
                    break;

                case 'active':
                    $query->where('status', 'active');
                    break;

                case 'inactive':
                    $query->where('status', '!=', 'active');
                    break;
            }
        }

        $riders = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('manager.riders.index', compact('riders', 'search', 'filter'));
    }

    /**
     * Show detailed information for a specific rider.
     *
     * Includes payment history, work logs, and vehicle assignment details.
     */
    public function show(User $rider): View
    {
        $manager = Auth::user();

        abort_unless($manager->isManager(), 403, 'Unauthorized. Manager access required.');
        $this->authorizeRiderBelongsToManager($rider, $manager);

        $rider->load([
            'riderProfile.assignedVehicle',
            'riderProfile.assignedRoute',
            'assignedVehicle',
        ]);

        $paymentHistory = Payment::where('rider_id', $rider->id)
            ->with('paymentPlan')
            ->orderByDesc('created_at')
            ->paginate(15);

        $workLogs = WorkLog::where('rider_id', $rider->id)
            ->orderByDesc('clock_in_at')
            ->limit(20)
            ->get();

        $totalPaid = Payment::where('rider_id', $rider->id)
            ->where('status', 'completed')
            ->sum('amount');

        $totalOutstanding = Payment::where('rider_id', $rider->id)
            ->where('status', 'pending')
            ->sum('amount');

        return view('manager.riders.show', compact(
            'rider',
            'paymentHistory',
            'workLogs',
            'totalPaid',
            'totalOutstanding',
        ));
    }

    /**
     * Show detailed payment status for a specific rider.
     *
     * Displays countdown to next payment due date, payment history timeline,
     * and overall payment compliance indicators.
     */
    public function paymentStatus(User $rider): View
    {
        $manager = Auth::user();

        abort_unless($manager->isManager(), 403, 'Unauthorized. Manager access required.');
        $this->authorizeRiderBelongsToManager($rider, $manager);

        $rider->load(['riderProfile.assignedVehicle', 'assignedVehicle']);

        // Get next upcoming payment
        $nextPayment = Payment::where('rider_id', $rider->id)
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->first();

        // Calculate countdown
        $countdown = null;
        $isOverdue = false;
        if ($nextPayment && $nextPayment->due_date) {
            $now = Carbon::now();
            $dueDate = $nextPayment->due_date;

            if ($dueDate->isPast()) {
                $isOverdue = true;
                $countdown = [
                    'days' => $now->diffInDays($dueDate),
                    'label' => 'Overdue',
                ];
            } else {
                $countdown = [
                    'days' => $now->diffInDays($dueDate),
                    'hours' => $now->diffInHours($dueDate) % 24,
                    'label' => 'Due',
                ];
            }
        }

        // Payment history for timeline
        $payments = Payment::where('rider_id', $rider->id)
            ->with('paymentPlan')
            ->orderByDesc('due_date')
            ->get();

        $totalPaid = $payments->where('status', 'completed')->sum('amount');
        $totalOverdue = $payments->filter(fn ($p) => $p->isOverdue())->sum('amount');
        $totalPending = $payments->where('status', 'pending')->sum('amount');

        // Payment compliance rate
        $completedCount = $payments->where('status', 'completed')->count();
        $totalCount = $payments->count();
        $complianceRate = $totalCount > 0 ? round(($completedCount / $totalCount) * 100, 1) : 0;

        return view('manager.riders.payment-status', compact(
            'rider',
            'nextPayment',
            'countdown',
            'isOverdue',
            'payments',
            'totalPaid',
            'totalOverdue',
            'totalPending',
            'complianceRate',
        ));
    }

    /**
     * Verify that a rider is assigned to a vehicle managed by this manager.
     */
    private function authorizeRiderBelongsToManager(User $rider, User $manager): void
    {
        $managesRider = Vehicle::where('assigned_manager_id', $manager->id)
            ->where('assigned_rider_id', $rider->id)
            ->exists();

        abort_unless($managesRider, 403, 'This rider is not assigned to your management.');
    }
}
