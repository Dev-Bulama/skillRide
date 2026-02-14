@extends('layouts.rider')
@section('title', 'Payments')
@section('content')
<div class="space-y-4 pb-20">
    <h1 class="text-xl font-bold text-gray-800 dark:text-white">Payments</h1>

    {{-- Summary --}}
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-emerald-50 rounded-xl p-4"><p class="text-xs text-emerald-600">Total Paid</p><p class="text-lg font-bold text-emerald-700">&#8358;{{ number_format($totalPaid, 2) }}</p></div>
        <div class="bg-red-50 rounded-xl p-4"><p class="text-xs text-red-600">Arrears</p><p class="text-lg font-bold text-red-700">&#8358;{{ number_format($arrears, 2) }}</p></div>
    </div>

    @if($nextDue)
    <div class="bg-blue-50 rounded-xl p-4">
        <p class="text-xs text-blue-600">Next Due Date</p>
        <p class="text-lg font-bold text-blue-700">{{ $nextDue->format('M d, Y') }}</p>
    </div>
    @endif

    {{-- Pay Now --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-800 dark:text-white mb-3">Make Payment</h3>
        <form method="POST" action="{{ route('rider.payments.pay') }}">
            @csrf
            <div class="flex gap-2">
                <input type="number" name="amount" placeholder="Amount (&#8358;)" min="100" required class="flex-1 rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg font-medium hover:from-emerald-600 hover:to-emerald-700">Pay</button>
            </div>
            @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </form>
    </div>

    {{-- History --}}
    <h3 class="font-semibold text-gray-800 dark:text-white">Payment History</h3>
    <div class="space-y-3">
        @forelse($payments as $payment)
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-semibold text-gray-800 dark:text-white">&#8358;{{ number_format($payment->amount, 2) }}</p>
                    <p class="text-xs text-gray-500">{{ $payment->created_at->format('M d, Y h:i A') }}</p>
                    <p class="text-xs text-gray-400">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }} | {{ $payment->reference }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">{{ ucfirst($payment->status) }}</span>
                    @if($payment->status === 'completed' && $payment->receipt_path)
                    <a href="{{ route('rider.payments.receipt', $payment) }}" class="block text-xs text-blue-600 mt-1">Receipt</a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-8 text-gray-400"><p>No payments yet.</p></div>
        @endforelse
    </div>
</div>
@endsection
