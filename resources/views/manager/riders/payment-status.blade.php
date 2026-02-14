@extends('layouts.manager')

@section('title', ($rider->user->name ?? 'Rider') . ' - Payment Status')
@section('header', 'Rider Payment Status')

@section('content')
<div class="space-y-6">
    {{-- Back Button --}}
    <a href="{{ route('manager.riders.show', $rider->id) }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to {{ $rider->user->name ?? 'Rider' }}
    </a>

    {{-- Rider Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center space-x-4">
            @if($rider->profile_photo)
                <img src="{{ Storage::url($rider->profile_photo) }}" alt="{{ $rider->user->name ?? '' }}" class="w-14 h-14 rounded-full object-cover">
            @else
                <div class="w-14 h-14 bg-blue-500 rounded-full flex items-center justify-center text-white text-xl font-bold">
                    {{ substr($rider->user->name ?? 'R', 0, 1) }}
                </div>
            @endif
            <div>
                <h2 class="text-lg font-bold text-gray-800">{{ $rider->user->name ?? 'Unknown Rider' }}</h2>
                <p class="text-sm text-gray-500">{{ $rider->vehicle->registration_number ?? $rider->vehicle->plate_number ?? 'No vehicle' }}</p>
            </div>
        </div>
    </div>

    {{-- Payment Countdown --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Next Payment Due</h3>

        @php
            $nextDue = $rider->next_payment_due ?? null;
            $daysRemaining = $nextDue ? now()->diffInDays(\Carbon\Carbon::parse($nextDue), false) : null;
            $hoursRemaining = $nextDue ? now()->diffInHours(\Carbon\Carbon::parse($nextDue), false) % 24 : null;
        @endphp

        <div class="flex items-center justify-center">
            <div class="text-center">
                @if($nextDue)
                    <div class="relative w-40 h-40 mx-auto">
                        <svg class="w-40 h-40 transform -rotate-90" viewBox="0 0 160 160">
                            <circle cx="80" cy="80" r="70" stroke="#E5E7EB" stroke-width="8" fill="none"/>
                            <circle cx="80" cy="80" r="70" stroke="{{ $daysRemaining <= 0 ? '#EF4444' : ($daysRemaining <= 3 ? '#F59E0B' : '#10B981') }}" stroke-width="8" fill="none"
                                stroke-dasharray="{{ 2 * 3.14159 * 70 }}"
                                stroke-dashoffset="{{ 2 * 3.14159 * 70 * (1 - max(0, min($daysRemaining, 30)) / 30) }}"
                                stroke-linecap="round"/>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-3xl font-bold {{ $daysRemaining <= 0 ? 'text-red-600' : ($daysRemaining <= 3 ? 'text-yellow-600' : 'text-gray-800') }}">
                                {{ max(0, $daysRemaining) }}
                            </span>
                            <span class="text-sm text-gray-500">days</span>
                            <span class="text-lg font-semibold text-gray-600">{{ $hoursRemaining }}h</span>
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-gray-500">Due: {{ \Carbon\Carbon::parse($nextDue)->format('M d, Y') }}</p>
                    @if($daysRemaining <= 0)
                        <p class="mt-1 text-sm font-semibold text-red-600">OVERDUE</p>
                    @endif
                @else
                    <p class="text-gray-400">No upcoming payment scheduled</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Payment Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center">
            <p class="text-sm text-gray-500">Total Paid</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">R{{ number_format($rider->total_paid ?? 0, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center">
            <p class="text-sm text-gray-500">This Month</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">R{{ number_format($rider->this_month_paid ?? 0, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center">
            <p class="text-sm text-gray-500">Arrears</p>
            <p class="text-2xl font-bold text-red-600 mt-1">R{{ number_format($rider->arrears ?? 0, 2) }}</p>
        </div>
    </div>

    {{-- Payment History --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment History</h3>

        @if(isset($rider->payments) && $rider->payments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Date</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Amount</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Method</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Reference</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($rider->payments as $payment)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-800">{{ $payment->created_at ? $payment->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                        <td class="py-3 px-4 font-semibold text-gray-800">R{{ number_format($payment->amount ?? 0, 2) }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
                        <td class="py-3 px-4 text-gray-600 font-mono text-xs">{{ $payment->reference ?? '-' }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $payment->status === 'completed' || $payment->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : ($payment->status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($payment->status ?? 'Unknown') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-sm">No payment history found</p>
        </div>
        @endif
    </div>
</div>
@endsection
