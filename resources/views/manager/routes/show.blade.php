@extends('layouts.manager')
@section('title', $route->name)
@section('header', $route->name)
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('manager.routes.index') }}" class="text-sm text-blue-600 hover:text-blue-700">&larr; Back to Routes</a>
        <a href="{{ route('manager.routes.track') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600">Live Tracking</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Route Details</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Start:</span><span class="text-gray-800 dark:text-white font-medium">{{ $route->start_location }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">End:</span><span class="text-gray-800 dark:text-white font-medium">{{ $route->end_location }}</span></div>
                @if($route->distance)<div class="flex justify-between"><span class="text-gray-500">Distance:</span><span class="text-gray-800 dark:text-white">{{ $route->distance }} km</span></div>@endif
                @if($route->estimated_duration)<div class="flex justify-between"><span class="text-gray-500">Duration:</span><span class="text-gray-800 dark:text-white">{{ $route->estimated_duration }} min</span></div>@endif
                @if($route->ward)<div class="flex justify-between"><span class="text-gray-500">Ward:</span><span class="text-gray-800 dark:text-white">{{ $route->ward }}</span></div>@endif
                @if($route->lga)<div class="flex justify-between"><span class="text-gray-500">LGA:</span><span class="text-gray-800 dark:text-white">{{ $route->lga }}</span></div>@endif
                @if($route->state)<div class="flex justify-between"><span class="text-gray-500">State:</span><span class="text-gray-800 dark:text-white">{{ $route->state }}</span></div>@endif
            </div>
        </div>

        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Assigned Riders ({{ $route->riderProfiles->count() }})</h3>
            </div>
            @if($route->riderProfiles->count() > 0)
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rider</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($route->riderProfiles as $profile)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-sm mr-3">{{ substr($profile->user->name ?? 'R', 0, 1) }}</div>
                                <a href="{{ route('manager.riders.show', $profile->user_id) }}" class="text-sm font-medium text-gray-800 dark:text-white hover:text-blue-600">{{ $profile->user->name ?? 'N/A' }}</a>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $profile->user->phone ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $profile->assignedVehicle->registration_number ?? 'None' }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full {{ ($profile->user->status ?? '') === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($profile->user->status ?? 'unknown') }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-8 text-gray-400"><p>No riders assigned to this route.</p></div>
            @endif
        </div>
    </div>
</div>
@endsection
