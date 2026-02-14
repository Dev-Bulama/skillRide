@extends('layouts.admin')
@section('title', 'Audit Detail')
@section('header', 'Audit Log Detail')
@section('content')
<div class="max-w-2xl bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
<div class="grid grid-cols-2 gap-4 text-sm">
<div><span class="text-gray-500">User:</span> <span class="font-medium text-gray-800 dark:text-white">{{ $log->user?->name ?? 'System' }}</span></div>
<div><span class="text-gray-500">Action:</span> <span class="font-medium text-gray-800 dark:text-white">{{ $log->action }}</span></div>
<div><span class="text-gray-500">Model:</span> <span class="font-medium text-gray-800 dark:text-white">{{ class_basename($log->model_type ?? 'N/A') }} #{{ $log->model_id ?? '' }}</span></div>
<div><span class="text-gray-500">Date:</span> <span class="font-medium text-gray-800 dark:text-white">{{ $log->created_at->format('M d, Y h:i A') }}</span></div>
<div><span class="text-gray-500">IP:</span> <span class="font-medium">{{ $log->ip_address }}</span></div>
</div>
<p class="text-gray-600 dark:text-gray-300">{{ $log->description }}</p>
@if($log->old_values)<div><h3 class="text-sm font-semibold text-gray-500 mb-1">Old Values</h3><pre class="bg-red-50 dark:bg-red-900/20 rounded-lg p-3 text-xs overflow-x-auto">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre></div>@endif
@if($log->new_values)<div><h3 class="text-sm font-semibold text-gray-500 mb-1">New Values</h3><pre class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3 text-xs overflow-x-auto">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre></div>@endif
<a href="{{ route('admin.audit.index') }}" class="text-sm text-emerald-600 hover:underline">&larr; Back</a>
</div>
@endsection
