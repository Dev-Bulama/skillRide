@extends('layouts.manager')
@section('title', 'Payout History')
@section('header', 'Payout History')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('manager.payments.index') }}" class="text-sm text-blue-600 hover:text-blue-700">&larr; Back to Payments</a>
        <a href="{{ route('manager.payments.payout') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600">New Payout Request</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bank</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requested</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($payouts as $payout)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-3 text-sm font-semibold text-gray-800 dark:text-white">N{{ number_format($payout->amount, 2) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                        {{ \Carbon\Carbon::parse($payout->period_start)->format('M d') }} - {{ \Carbon\Carbon::parse($payout->period_end)->format('M d, Y') }}
                    </td>
                    <td class="px-4 py-3">
                        <p class="text-sm text-gray-800 dark:text-white">{{ $payout->bank_name }}</p>
                        <p class="text-xs text-gray-500">{{ $payout->bank_account_number }} ({{ $payout->bank_account_name }})</p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full {{ $payout->status === 'approved' ? 'bg-green-100 text-green-800' : ($payout->status === 'rejected' ? 'bg-red-100 text-red-800' : ($payout->status === 'processing' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">{{ ucfirst($payout->status) }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $payout->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No payout requests yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $payouts->links() }}</div>
</div>
@endsection
