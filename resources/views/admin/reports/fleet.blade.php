@extends('layouts.admin')
@section('title', 'Fleet Report')
@section('header', 'Fleet Utilization')
@section('content')
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4"><p class="text-xs text-gray-500">Total</p><p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $report['total_vehicles'] }}</p></div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4"><p class="text-xs text-gray-500">Active</p><p class="text-2xl font-bold text-green-600">{{ $report['active'] }}</p></div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4"><p class="text-xs text-gray-500">Maintenance</p><p class="text-2xl font-bold text-yellow-600">{{ $report['maintenance'] }}</p></div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4"><p class="text-xs text-gray-500">Utilization</p><p class="text-2xl font-bold text-blue-600">{{ $report['utilization_rate'] }}%</p></div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6"><h3 class="font-semibold text-gray-800 dark:text-white mb-4">By Type</h3>
@foreach($report['by_type'] as $type => $count)<div class="flex justify-between py-2 border-b border-gray-100"><span class="text-sm text-gray-600">{{ ucfirst(str_replace('_',' ',$type)) }}</span><span class="font-medium">{{ $count }}</span></div>@endforeach</div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6"><h3 class="font-semibold text-gray-800 dark:text-white mb-4">Maintenance Costs</h3>
<p class="text-3xl font-bold text-emerald-600">&#8358;{{ number_format($report['maintenance_costs'], 2) }}</p><p class="text-sm text-gray-500 mt-1">Total completed maintenance costs</p></div>
</div>
@endsection
