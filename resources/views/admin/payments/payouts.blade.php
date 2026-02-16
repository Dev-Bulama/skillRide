@extends('layouts.admin')
@section('title', 'Payout Requests')
@section('header', 'Manager Payout Requests')

@section('content')
<div class="space-y-6">
    {{-- Payout Requests Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Payout Requests</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Manager Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bank Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($payouts as $payout)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 font-medium text-sm">{{ substr($payout->manager->name ?? 'N', 0, 1) }}</div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">{{ $payout->manager->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">&#8358;{{ number_format($payout->amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ \Carbon\Carbon::parse($payout->period_start)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($payout->period_end)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $payout->bank_name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $payout->account_number ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $payout->account_name ?? '' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $sc = match($payout->status) {
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-emerald-100 text-emerald-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'paid' => 'bg-blue-100 text-blue-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $sc }}">{{ ucfirst($payout->status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($payout->status === 'pending')
                            <div class="flex items-center justify-end space-x-2">
                                <form method="POST" action="{{ route('admin.payouts.approve', $payout) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-500 text-white text-xs font-medium rounded-lg hover:bg-emerald-600 transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.payouts.reject', $payout) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Reject
                                    </button>
                                </form>
                            </div>
                            @else
                            <span class="text-sm text-gray-400">--</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">No payout requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payouts->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">{{ $payouts->links() }}</div>
        @endif
    </div>
</div>
@endsection
