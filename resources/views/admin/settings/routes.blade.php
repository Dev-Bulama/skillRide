@extends('layouts.admin')
@section('title', 'Routes')
@section('header', 'Route Management')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
<div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
<thead class="bg-gray-50 dark:bg-gray-900"><tr><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">From/To</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Manager</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th><th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th></tr></thead>
<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
@forelse($routes as $route)
<tr><td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $route->name }}</td><td class="px-4 py-3 text-sm text-gray-600">{{ $route->start_location }} → {{ $route->end_location }}</td><td class="px-4 py-3 text-sm text-gray-600">{{ $route->assignedManager?->name ?? 'N/A' }}</td><td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full {{ $route->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ ucfirst($route->status) }}</span></td>
<td class="px-4 py-3 text-right"><form method="POST" action="{{ route('admin.settings.routes.delete', $route) }}" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-red-600 text-sm hover:underline">Delete</button></form></td></tr>
@empty<tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">No routes.</td></tr>@endforelse
</tbody></table></div>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
<h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Add Route</h3>
<form method="POST" action="{{ route('admin.settings.routes.store') }}" class="space-y-3">@csrf
<div><input type="text" name="name" placeholder="Route Name *" required class="block w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500"></div>
<div><input type="text" name="start_location" placeholder="Start Location *" required class="block w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500"></div>
<div><input type="text" name="end_location" placeholder="End Location *" required class="block w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500"></div>
<div><input type="text" name="ward" placeholder="Ward" class="block w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500"></div>
<div><input type="text" name="lga" placeholder="LGA" class="block w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500"></div>
<div><input type="text" name="state" placeholder="State" class="block w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500"></div>
<div><textarea name="description" placeholder="Description" rows="2" class="block w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500"></textarea></div>
<button type="submit" class="w-full py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600">Create Route</button>
</form></div></div>
@endsection
