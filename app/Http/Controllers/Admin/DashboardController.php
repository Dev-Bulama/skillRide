<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FraudAlert;
use App\Models\Payment;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\PaymentService;

class DashboardController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    public function index()
    {
        $totalRiders = User::where('role', 'rider')->count();
        $activeVehicles = Vehicle::where('status', 'active')->count();
        $revenueStats = $this->paymentService->getRevenueStats();
        $pendingVerifications = User::where('role', 'rider')->where('is_verified', false)->count();

        $recentPayments = Payment::with('rider')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $recentAlerts = FraudAlert::with('user')
            ->whereIn('status', ['open', 'investigating'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $riderGrowth = User::where('role', 'rider')
            ->selectRaw("strftime('%Y-%m', created_at) as month, COUNT(*) as count")
            ->groupByRaw("strftime('%Y-%m', created_at)")
            ->orderBy('month')
            ->limit(12)
            ->pluck('count', 'month');

        return view('admin.dashboard.index', [
            'totalRiders' => $totalRiders,
            'activeVehicles' => $activeVehicles,
            'monthlyRevenue' => $revenueStats['current_month'],
            'revenueGrowth' => $revenueStats['growth_percentage'],
            'pendingVerifications' => $pendingVerifications,
            'recentPayments' => $recentPayments,
            'recentAlerts' => $recentAlerts,
            'riderGrowth' => $riderGrowth,
            'revenueData' => $revenueStats['monthly_data'],
        ]);
    }
}
