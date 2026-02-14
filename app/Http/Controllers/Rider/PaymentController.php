<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
        protected PaystackService $paystackService
    ) {}

    public function index()
    {
        $rider = auth()->user();
        $payments = $this->paymentService->getRiderPaymentHistory($rider);
        $nextDue = $this->paymentService->getNextDueDate($rider);
        $arrears = $this->paymentService->calculateArrears($rider);
        $totalPaid = $payments->where('status', 'completed')->sum('amount');

        return view('rider.payments.index', compact('payments', 'nextDue', 'arrears', 'totalPaid'));
    }

    public function pay(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:100']);

        $rider = auth()->user();
        $reference = 'SR-' . strtoupper(Str::random(12));

        $result = $this->paystackService->initializeTransaction(
            $rider->email,
            $request->amount,
            $reference,
            route('rider.payments.callback')
        );

        if ($result['status'] ?? false) {
            $this->paymentService->processPaystackPayment($rider, $request->amount, $reference);
            return redirect($result['data']['authorization_url']);
        }

        return back()->with('error', 'Payment initialization failed. Please try again.');
    }

    public function callback(Request $request)
    {
        $reference = $request->query('reference') ?? $request->query('trxref');

        if (!$reference) {
            return redirect()->route('rider.payments.index')->with('error', 'Invalid payment reference.');
        }

        $result = $this->paymentService->verifyPaystackPayment($reference);

        if ($result['status'] ?? false) {
            return redirect()->route('rider.payments.index')->with('success', 'Payment successful!');
        }

        return redirect()->route('rider.payments.index')->with('error', 'Payment verification failed.');
    }

    public function receipt(Payment $payment)
    {
        abort_if($payment->rider_id !== auth()->id(), 403);

        if (!$payment->receipt_path) {
            $this->paymentService->generateReceipt($payment);
            $payment->refresh();
        }

        return response()->download(storage_path('app/' . $payment->receipt_path));
    }
}
