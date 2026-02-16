<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentPlan;
use App\Models\PayoutRequest;
use App\Services\AuditService;
use App\Services\PaymentService;
use App\Services\ReportService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
        protected AuditService $auditService,
        protected ReportService $reportService
    ) {}

    public function index(Request $request)
    {
        $query = Payment::with('rider');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }
        if ($request->filled('search')) {
            $query->whereHas('rider', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }

        $payments = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $summary = [
            'total_collected' => Payment::where('status', 'completed')->sum('amount'),
            'pending' => Payment::where('status', 'pending')->sum('amount'),
            'overdue' => Payment::where('status', 'pending')->where('due_date', '<', now())->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'summary'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['rider', 'paymentPlan', 'approvedBy']);
        return view('admin.payments.show', compact('payment'));
    }

    public function approve(Payment $payment)
    {
        $this->paymentService->approvePayment($payment, auth()->user());
        $this->auditService->log('approved', auth()->user(), Payment::class, $payment->id, null, null, 'Approved payment ' . $payment->reference);
        return back()->with('success', 'Payment approved.');
    }

    public function configurePlans()
    {
        $plans = PaymentPlan::orderBy('name')->get();
        return view('admin.payments.plans', compact('plans'));
    }

    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:daily,weekly,monthly',
            'vehicle_type' => 'required|in:keke_napep,car,bus,truck',
            'duration_days' => 'required|integer|min:1',
            'total_amount' => 'nullable|numeric|min:0',
        ]);

        PaymentPlan::create($validated);

        return back()->with('success', 'Payment plan created.');
    }

    public function updatePlan(Request $request, PaymentPlan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:daily,weekly,monthly',
            'is_active' => 'boolean',
        ]);

        $plan->update($validated);
        return back()->with('success', 'Plan updated.');
    }

    public function deletePlan(PaymentPlan $plan)
    {
        $plan->delete();
        return back()->with('success', 'Plan deleted.');
    }

    public function arrears()
    {
        $overduePayments = $this->paymentService->getOverduePayments();
        $totalArrears = $overduePayments->sum('amount');
        return view('admin.payments.arrears', compact('overduePayments', 'totalArrears'));
    }

    public function revenue()
    {
        $stats = $this->paymentService->getRevenueStats();
        return view('admin.payments.revenue', compact('stats'));
    }

    public function togglePlan(PaymentPlan $plan)
    {
        $plan->update([
            'status' => ($plan->status ?? 'active') === 'active' ? 'inactive' : 'active',
        ]);
        return back()->with('success', 'Plan status updated.');
    }

    public function payoutRequests()
    {
        $payouts = PayoutRequest::with('manager')
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('admin.payments.payouts', compact('payouts'));
    }

    public function approvePayout(PayoutRequest $payout)
    {
        $payout->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        return back()->with('success', 'Payout request approved.');
    }

    public function rejectPayout(PayoutRequest $payout)
    {
        $payout->update(['status' => 'rejected']);
        return back()->with('success', 'Payout request rejected.');
    }

    public function exportPayments(Request $request)
    {
        $payments = Payment::with('rider')
            ->when($request->from, fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('created_at', '<=', $request->to))
            ->get();

        $data = $payments->map(fn($p) => [
            'Reference' => $p->reference,
            'Rider' => $p->rider?->name,
            'Amount' => $p->amount,
            'Method' => $p->payment_method,
            'Status' => $p->status,
            'Date' => $p->created_at->format('Y-m-d H:i'),
        ])->toArray();

        $path = $this->reportService->exportToCsv($data, 'payments-' . date('Y-m-d') . '.csv');
        return response()->download(storage_path('app/' . $path));
    }
}
