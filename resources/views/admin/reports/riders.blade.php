@extends('layouts.admin')
@section('title', 'Rider Reports')
@section('header', 'Rider Performance')
@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
<thead class="bg-gray-50 dark:bg-gray-900"><tr><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rider</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th><th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th></tr></thead>
<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
@forelse($riders as $rider)
<tr><td class="px-4 py-3"><p class="text-sm font-medium text-gray-900 dark:text-white">{{ $rider->name }}</p><p class="text-xs text-gray-500">{{ $rider->phone }}</p></td>
<td class="px-4 py-3 text-sm text-gray-600">{{ $rider->riderProfile?->assignedVehicle?->registration_number ?? 'N/A' }}</td>
<td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full {{ $rider->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ ucfirst($rider->status) }}</span></td>
<td class="px-4 py-3 text-right"><a href="{{ route('admin.riders.show', $rider) }}" class="text-blue-600 text-sm hover:underline">View</a></td></tr>
@empty<tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No riders.</td></tr>@endforelse
</tbody></table>
<div class="px-4 py-3 border-t">{{ $riders->links() }}</div>
</div>
@endsection
