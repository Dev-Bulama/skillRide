@extends('layouts.manager')
@section('title', 'Routes')
@section('header', 'Routes')
@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($routes as $route)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition">
            <div class="flex items-start justify-between mb-3">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $route->name }}</h3>
                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">{{ $route->rider_profiles_count }} riders</span>
            </div>
            <div class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/></svg>
                    <span>{{ $route->start_location }}</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>{{ $route->end_location }}</span>
                </div>
                @if($route->distance)
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    <span>{{ $route->distance }} km</span>
                    @if($route->estimated_duration) <span class="ml-2 text-gray-400">~{{ $route->estimated_duration }} min</span>@endif
                </div>
                @endif
                @if($route->ward || $route->lga)
                <div class="text-xs text-gray-400 mt-1">{{ implode(' / ', array_filter([$route->ward, $route->lga, $route->state])) }}</div>
                @endif
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('manager.routes.show', $route) }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View Details &rarr;</a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-gray-400">
            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
            <p>No routes assigned to you yet.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
