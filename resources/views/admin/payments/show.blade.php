@extends('layouts.admin')
@section('title', 'Payment Details')
@section('header', 'Payment #' . ($payment->reference ?? $payment->id))

@section('content')
<div class="space-y-6">
    <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-emerald-600">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Payments
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Payment Details --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Payment Details</h3>
                    @php
                        $statusClass = match($payment->status) {
                            'paid', 'approved', 'completed' => 'bg-emerald-100 text-emerald-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'overdue' => 'bg-red-100 text-red-800',
                            'failed' => 'bg-red-100 text-red-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full {{ $statusClass }}">{{ ucfirst($payment->status) }}</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Amount</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">&#8358;{{ number_format($payment->amount, 2) }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Payment Method</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white capitalize">{{ $payment->payment_method ?? 'N/A' }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Reference</p>
                        <p class="text-sm font-mono font-medium text-gray-900 dark:text-white">{{ $payment->reference ?? 'N/A' }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Date</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $payment->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @if($payment->due_date)
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Due Date</p>
                        <p class="text-sm font-medium {{ $payment->due_date->isPast() && $payment->status !== 'paid' ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">{{ $payment->due_date->format('M d, Y') }}</p>
                    </div>
                    @endif
                    @if($payment->notes)
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg sm:col-span-2">
                        <p class="text-xs text-gray-500 mb-1">Notes</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $payment->notes }}</p>
                    </div>
                    @endif
                </div>

                @if($payment->status === 'pending')
                <div class="mt-6 flex gap-3">
                    <form method="POST" action="{{ route('admin.payments.approve', $payment) }}">
                        @csrf
                        <button type="submit" class="px-6 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600">Approve Payment</button>
                    </form>
                    <form method="POST" action="{{ route('admin.payments.reject', $payment) }}" onsubmit="return confirm('Reject this payment?')">
                        @csrf
                        <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded-lg text-sm font-medium hover:bg-red-600">Reject</button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        {{-- Rider Info --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Rider Information</h3>
                @if($payment->rider)
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 font-bold text-xl mb-3">
                        {{ strtoupper(substr($payment->rider->name, 0, 2)) }}
                    </div>
                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $payment->rider->name }}</h4>
                    <p class="text-sm text-gray-500 mt-1">{{ $payment->rider->email }}</p>
                    <p class="text-sm text-gray-500">{{ $payment->rider->phone ?? '' }}</p>
                    <a href="{{ route('admin.riders.show', $payment->rider) }}" class="mt-4 inline-flex items-center text-sm text-emerald-600 hover:text-emerald-700 font-medium">View Profile</a>
                </div>
                @else
                <p class="text-sm text-gray-500 text-center">Rider information not available.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
