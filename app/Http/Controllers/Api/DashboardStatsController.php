<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;

class DashboardStatsController extends Controller
{
    public function adminStats(): JsonResponse
    {
        return response()->json([
            'total_riders' => User::where('role', 'rider')->count(),
            'active_riders' => User::where('role', 'rider')->where('status', 'active')->count(),
            'total_vehicles' => Vehicle::count(),
            'active_vehicles' => Vehicle::where('status', 'active')->count(),
            'monthly_revenue' => Payment::where('status', 'completed')->whereMonth('paid_at', now()->month)->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'overdue_payments' => Payment::where('status', 'pending')->where('due_date', '<', now())->count(),
            'pending_verifications' => User::where('role', 'rider')->where('is_verified', false)->count(),
        ]);
    }

    public function managerStats(): JsonResponse
    {
        $riderIds = Vehicle::where('assigned_manager_id', auth()->id())
            ->whereNotNull('assigned_rider_id')
            ->pluck('assigned_rider_id');

        return response()->json([
            'rider_count' => $riderIds->count(),
            'vehicle_count' => Vehicle::where('assigned_manager_id', auth()->id())->count(),
            'monthly_collections' => Payment::whereIn('rider_id', $riderIds)->where('status', 'completed')->whereMonth('paid_at', now()->month)->sum('amount'),
            'overdue_count' => Payment::whereIn('rider_id', $riderIds)->where('status', 'pending')->where('due_date', '<', now())->count(),
        ]);
    }

    public function riderStats(): JsonResponse
    {
        $rider = auth()->user();
        return response()->json([
            'total_paid' => Payment::where('rider_id', $rider->id)->where('status', 'completed')->sum('amount'),
            'arrears' => Payment::where('rider_id', $rider->id)->where('status', 'pending')->where('due_date', '<', now())->sum('amount'),
            'next_due' => Payment::where('rider_id', $rider->id)->where('status', 'pending')->where('due_date', '>=', now())->orderBy('due_date')->first()?->due_date,
        ]);
    }
}
