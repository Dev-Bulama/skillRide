@extends('layouts.admin')
@section('title', 'Fraud Alerts')
@section('header', 'Fraud Alerts')
@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
<thead class="bg-gray-50 dark:bg-gray-900"><tr><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Severity</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th><th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th></tr></thead>
<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
@forelse($alerts as $alert)
<tr><td class="px-4 py-3 text-sm text-gray-800 dark:text-white">{{ ucfirst(str_replace('_',' ',$alert->type)) }}</td>
<td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full {{ $alert->severity === 'critical' ? 'bg-red-100 text-red-800' : ($alert->severity === 'high' ? 'bg-orange-100 text-orange-800' : ($alert->severity === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">{{ ucfirst($alert->severity) }}</span></td>
<td class="px-4 py-3 text-sm text-gray-600">{{ $alert->user?->name ?? 'N/A' }}</td>
<td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full {{ $alert->status === 'open' ? 'bg-red-100 text-red-800' : ($alert->status === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">{{ ucfirst($alert->status) }}</span></td>
<td class="px-4 py-3 text-sm text-gray-500">{{ $alert->created_at->format('M d, Y') }}</td>
<td class="px-4 py-3 text-right space-x-2">
@if($alert->status === 'open')<form method="POST" action="{{ route('admin.fraud.resolve', $alert) }}" class="inline">@csrf<button class="text-green-600 text-sm hover:underline">Resolve</button></form>
<form method="POST" action="{{ route('admin.fraud.dismiss', $alert) }}" class="inline">@csrf<button class="text-gray-600 text-sm hover:underline">Dismiss</button></form>@endif
</td></tr>
@empty<tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">No fraud alerts.</td></tr>@endforelse
</tbody></table><div class="px-4 py-3 border-t">{{ $alerts->links() }}</div></div>
@endsection
