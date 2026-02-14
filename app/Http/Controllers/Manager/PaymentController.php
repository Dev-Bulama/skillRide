<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PayoutRequest;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $riderIds = Vehicle::where('assigned_manager_id', auth()->id())
            ->whereNotNull('assigned_rider_id')
            ->pluck('assigned_rider_id');

        $query = Payment::whereIn('rider_id', $riderIds)->with('rider');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $summary = [
            'total_collected' => Payment::whereIn('rider_id', $riderIds)->where('status', 'completed')->sum('amount'),
            'pending' => Payment::whereIn('rider_id', $riderIds)->where('status', 'pending')->sum('amount'),
            'overdue' => Payment::whereIn('rider_id', $riderIds)->where('status', 'pending')->where('due_date', '<', now())->sum('amount'),
        ];

        return view('manager.payments.index', compact('payments', 'summary'));
    }

    public function requestPayout()
    {
        $manager = auth()->user();
        $manager->load('managerProfile');
        return view('manager.payments.payout', compact('manager'));
    }

    public function storePayout(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'bank_name' => 'required|string',
            'bank_account_number' => 'required|string',
            'bank_account_name' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        PayoutRequest::create(array_merge($validated, [
            'manager_id' => auth()->id(),
            'status' => 'pending',
        ]));

        return redirect()->route('manager.payments.history')->with('success', 'Payout request submitted.');
    }

    public function payoutHistory()
    {
        $payouts = PayoutRequest::where('manager_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(15);
        return view('manager.payments.history', compact('payouts'));
    }

    public function downloadReport(Request $request)
    {
        $riderIds = Vehicle::where('assigned_manager_id', auth()->id())
            ->whereNotNull('assigned_rider_id')
            ->pluck('assigned_rider_id');

        $payments = Payment::whereIn('rider_id', $riderIds)
            ->with('rider')
            ->orderByDesc('created_at')
            ->get();

        $data = $payments->map(fn($p) => [
            'Rider' => $p->rider?->name,
            'Amount' => $p->amount,
            'Status' => $p->status,
            'Method' => $p->payment_method,
            'Date' => $p->created_at->format('Y-m-d'),
        ])->toArray();

        $reportService = app(\App\Services\ReportService::class);
        $path = $reportService->exportToCsv($data, 'manager-report-' . date('Y-m-d') . '.csv');
        return response()->download(storage_path('app/' . $path));
    }
}
