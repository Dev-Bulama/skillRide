<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\WorkLog;
use App\Services\PaymentService;

class DashboardController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    public function index()
    {
        $rider = auth()->user();
        $rider->load('riderProfile.assignedVehicle', 'riderProfile.assignedRoute');

        $vehicle = $rider->riderProfile?->assignedVehicle;
        $nextDue = $this->paymentService->getNextDueDate($rider);
        $arrears = $this->paymentService->calculateArrears($rider);

        $paymentSummary = $this->paymentService->getRevenueStats();
        $riderPayments = $this->paymentService->getRiderPaymentHistory($rider);

        $totalPaid = $riderPayments->where('status', 'completed')->sum('amount');
        $thisMonthPaid = $riderPayments->where('status', 'completed')
            ->filter(fn($p) => $p->paid_at && $p->paid_at->isCurrentMonth())
            ->sum('amount');

        $currentWorkLog = WorkLog::where('rider_id', $rider->id)
            ->whereNull('clock_out_at')
            ->latest()
            ->first();

        $isClockIn = $currentWorkLog !== null;

        $nextPayment = $rider->payments()
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->first();

        return view('rider.dashboard.index', compact(
            'rider', 'vehicle', 'nextDue', 'arrears', 'totalPaid', 'thisMonthPaid',
            'isClockIn', 'currentWorkLog', 'nextPayment'
        ));
    }
}
