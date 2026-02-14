@extends('layouts.admin')
@section('title', 'Alert Detail')
@section('header', 'Fraud Alert Detail')
@section('content')
<div class="max-w-2xl bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
<div class="flex items-center justify-between"><h2 class="text-lg font-bold text-gray-800 dark:text-white">{{ ucfirst(str_replace('_',' ',$alert->type)) }}</h2>
<span class="px-3 py-1 text-sm rounded-full {{ $alert->severity === 'critical' ? 'bg-red-100 text-red-800' : ($alert->severity === 'high' ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst($alert->severity) }}</span></div>
<p class="text-gray-600 dark:text-gray-300">{{ $alert->description }}</p>
<div class="grid grid-cols-2 gap-4 text-sm"><div><span class="text-gray-500">User:</span> <span class="font-medium">{{ $alert->user?->name ?? 'N/A' }}</span></div><div><span class="text-gray-500">Vehicle:</span> <span class="font-medium">{{ $alert->vehicle?->registration_number ?? 'N/A' }}</span></div><div><span class="text-gray-500">Status:</span> <span class="font-medium">{{ ucfirst($alert->status) }}</span></div><div><span class="text-gray-500">Date:</span> <span class="font-medium">{{ $alert->created_at->format('M d, Y h:i A') }}</span></div></div>
@if($alert->data)<div><h3 class="text-sm font-semibold text-gray-500 mb-2">Data</h3><pre class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 text-xs overflow-x-auto">{{ json_encode($alert->data, JSON_PRETTY_PRINT) }}</pre></div>@endif
@if($alert->status === 'open')<div class="flex gap-3"><form method="POST" action="{{ route('admin.fraud.resolve', $alert) }}">@csrf<button class="px-4 py-2 bg-green-500 text-white rounded-lg text-sm hover:bg-green-600">Resolve</button></form><form method="POST" action="{{ route('admin.fraud.dismiss', $alert) }}">@csrf<button class="px-4 py-2 bg-gray-500 text-white rounded-lg text-sm hover:bg-gray-600">Dismiss</button></form></div>@endif
<a href="{{ route('admin.fraud.index') }}" class="text-sm text-emerald-600 hover:underline">&larr; Back to Alerts</a>
</div>
@endsection
