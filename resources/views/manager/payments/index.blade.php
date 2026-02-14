@extends('layouts.manager')
@section('title', 'Payments')
@section('header', 'Payment Overview')
@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500">Total Collected</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">N{{ number_format($summary['total_collected'], 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-2xl font-bold text-yellow-600 mt-1">N{{ number_format($summary['pending'], 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500">Overdue</p>
            <p class="text-2xl font-bold text-red-600 mt-1">N{{ number_format($summary['overdue'], 2) }}</p>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex gap-2">
            <a href="{{ route('manager.payments.payout') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600">Request Payout</a>
            <a href="{{ route('manager.payments.report') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200">Download Report</a>
        </div>
        <form method="GET" class="flex gap-2">
            <select name="status" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Statuses</option>
                @foreach(['pending', 'completed', 'failed', 'overdue'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rider</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paid At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-3">
                        <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $payment->rider->name ?? 'N/A' }}</p>
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-800 dark:text-white">N{{ number_format($payment->amount, 2) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst($payment->status) }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $payment->due_date ? \Carbon\Carbon::parse($payment->due_date)->format('M d, Y') : '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('M d, Y') : '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No payments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $payments->links() }}</div>
</div>
@endsection
