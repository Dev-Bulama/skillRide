@extends('layouts.admin')
@section('title', 'Revenue')
@section('header', 'Revenue Overview')

@section('content')
<div class="space-y-6">
    {{-- Revenue Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <p class="text-sm text-gray-500">Current Month</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">&#8358;{{ number_format($stats['current_month'] ?? 0, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <p class="text-sm text-gray-500">Previous Month</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">&#8358;{{ number_format($stats['previous_month'] ?? 0, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <p class="text-sm text-gray-500">Total Revenue</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">&#8358;{{ number_format($stats['total'] ?? 0, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <p class="text-sm text-gray-500">Growth</p>
            <div class="flex items-center mt-1">
                @php $growth = $stats['growth_percentage'] ?? 0; @endphp
                <p class="text-2xl font-bold {{ $growth >= 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ number_format($growth, 1) }}%</p>
                @if($growth >= 0)
                <svg class="w-5 h-5 text-emerald-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                @else
                <svg class="w-5 h-5 text-red-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                @endif
            </div>
        </div>
    </div>

    {{-- Monthly Revenue Table --}}
    @if(!empty($stats['monthly_data']))
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Monthly Revenue Breakdown</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payments</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trend</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($stats['monthly_data'] as $month)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $month['label'] ?? $month['month'] ?? '' }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-emerald-600">&#8358;{{ number_format($month['revenue'] ?? $month['amount'] ?? 0, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $month['count'] ?? 0 }}</td>
                        <td class="px-6 py-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                @php $maxRev = collect($stats['monthly_data'])->max('revenue') ?: 1; $pct = (($month['revenue'] ?? $month['amount'] ?? 0) / $maxRev) * 100; @endphp
                                <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
