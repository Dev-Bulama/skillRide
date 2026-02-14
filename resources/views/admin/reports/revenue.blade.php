@extends('layouts.admin')
@section('title', 'Revenue Report')
@section('header', 'Revenue Report')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6"><p class="text-sm text-gray-500">Total Revenue</p><p class="text-2xl font-bold text-emerald-600">&#8358;{{ number_format($report['total_revenue'], 2) }}</p></div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6"><p class="text-sm text-gray-500">Transactions</p><p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $report['total_transactions'] }}</p></div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6"><p class="text-sm text-gray-500">Average Payment</p><p class="text-2xl font-bold text-blue-600">&#8358;{{ number_format($report['average_payment'], 2) }}</p></div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
<h3 class="font-semibold text-gray-800 dark:text-white mb-4">By Payment Method</h3>
@foreach($report['by_method'] as $method => $amount)
<div class="flex justify-between py-2 border-b border-gray-100"><span class="text-sm text-gray-600">{{ ucfirst(str_replace('_',' ',$method)) }}</span><span class="font-medium text-gray-800 dark:text-white">&#8358;{{ number_format($amount, 2) }}</span></div>
@endforeach
</div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
<h3 class="font-semibold text-gray-800 dark:text-white mb-4">Daily Breakdown</h3>
<div class="max-h-64 overflow-y-auto">
@foreach($report['daily'] as $date => $amount)
<div class="flex justify-between py-2 border-b border-gray-100"><span class="text-sm text-gray-600">{{ $date }}</span><span class="font-medium text-gray-800 dark:text-white">&#8358;{{ number_format($amount, 2) }}</span></div>
@endforeach
</div></div></div>
@endsection
