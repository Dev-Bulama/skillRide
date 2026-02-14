@extends('layouts.admin')
@section('title', 'Payment Arrears')
@section('header', 'Payment Arrears')

@section('content')
<div class="space-y-6">
    {{-- Alert Banner --}}
    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-red-800">Total Outstanding Arrears</h3>
                <p class="text-3xl font-bold text-red-600">&#8358;{{ number_format($totalArrears, 2) }}</p>
            </div>
        </div>
    </div>

    {{-- Overdue Payments Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Overdue Payments</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rider</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount Owed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days Overdue</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($overduePayments as $payment)
                    <tr class="hover:bg-red-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-red-600 font-medium text-sm">{{ substr($payment->rider->name ?? 'N', 0, 1) }}</div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $payment->rider->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $payment->rider->phone ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-red-600">&#8358;{{ number_format($payment->amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $payment->due_date ? $payment->due_date->format('M d, Y') : 'N/A' }}</td>
                        <td class="px-6 py-4">
                            @php $daysOverdue = $payment->due_date ? now()->diffInDays($payment->due_date) : 0; @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $daysOverdue > 7 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $daysOverdue }} days
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.payments.show', $payment) }}" class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No overdue payments. All clear!</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($overduePayments, 'hasPages') && $overduePayments->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">{{ $overduePayments->links() }}</div>
        @endif
    </div>
</div>
@endsection
