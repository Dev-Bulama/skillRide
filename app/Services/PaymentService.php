<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\PaymentPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(
        protected PaystackService $paystackService,
        protected NotificationService $notificationService
    ) {}

    public function createPayment(array $data): Payment
    {
        $data['reference'] = $data['reference'] ?? 'PAY-' . strtoupper(Str::random(12));
        return Payment::create($data);
    }

    public function processPaystackPayment(User $rider, float $amount, string $reference): Payment
    {
        $payment = $this->createPayment([
            'rider_id' => $rider->id,
            'amount' => $amount,
            'payment_method' => 'paystack',
            'reference' => $reference,
            'paystack_reference' => $reference,
            'status' => 'pending',
            'payment_date' => now(),
            'due_date' => now(),
        ]);

        return $payment;
    }

    public function verifyPaystackPayment(string $reference): array
    {
        $result = $this->paystackService->verifyTransaction($reference);

        if ($result['status'] && ($result['data']['status'] ?? '') === 'success') {
            $payment = Payment::where('paystack_reference', $reference)->first();
            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);
            }
        }

        return $result;
    }

    public function approvePayment(Payment $payment, User $approver): void
    {
        $payment->update([
            'status' => 'completed',
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'paid_at' => $payment->paid_at ?? now(),
        ]);
    }

    public function getOverduePayments(): Collection
    {
        return Payment::where('status', 'pending')
            ->where('due_date', '<', now())
            ->with('rider')
            ->orderBy('due_date')
            ->get();
    }

    public function getRiderPaymentHistory(User $rider): Collection
    {
        return Payment::where('rider_id', $rider->id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getRevenueStats(string $period = 'monthly'): array
    {
        $now = Carbon::now();

        $currentRevenue = Payment::where('status', 'completed')
            ->whereMonth('paid_at', $now->month)
            ->whereYear('paid_at', $now->year)
            ->sum('amount');

        $previousRevenue = Payment::where('status', 'completed')
            ->whereMonth('paid_at', $now->copy()->subMonth()->month)
            ->whereYear('paid_at', $now->copy()->subMonth()->year)
            ->sum('amount');

        $totalRevenue = Payment::where('status', 'completed')->sum('amount');

        $monthlyData = Payment::where('status', 'completed')
            ->whereYear('paid_at', $now->year)
            ->selectRaw("DATE_FORMAT(paid_at, '%m') as month, SUM(amount) as total")
            ->groupByRaw("DATE_FORMAT(paid_at, '%m')")
            ->pluck('total', 'month')
            ->toArray();

        return [
            'current_month' => (float) $currentRevenue,
            'previous_month' => (float) $previousRevenue,
            'total' => (float) $totalRevenue,
            'growth_percentage' => $previousRevenue > 0
                ? round((($currentRevenue - $previousRevenue) / $previousRevenue) * 100, 1)
                : 0,
            'monthly_data' => $monthlyData,
        ];
    }

    public function calculateArrears(User $rider): float
    {
        return (float) Payment::where('rider_id', $rider->id)
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->sum('amount');
    }

    public function getNextDueDate(User $rider): ?Carbon
    {
        $nextPayment = Payment::where('rider_id', $rider->id)
            ->where('status', 'pending')
            ->where('due_date', '>=', now())
            ->orderBy('due_date')
            ->first();

        return $nextPayment?->due_date;
    }

    public function generateReceipt(Payment $payment): string
    {
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('exports.receipt', ['payment' => $payment->load('rider')]);
        $path = 'receipts/receipt-' . $payment->reference . '.pdf';
        $fullPath = storage_path('app/' . $path);

        if (!is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        $pdf->save($fullPath);
        $payment->update(['receipt_path' => $path]);

        return $path;
    }
}
