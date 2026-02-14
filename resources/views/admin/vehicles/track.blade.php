@extends('layouts.admin')
@section('title', 'Track Vehicle')
@section('header', 'Track Vehicle - ' . $vehicle->registration_number)
@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $vehicle->registration_number }}</h3>
            <p class="text-sm text-gray-500">{{ $vehicle->make }} {{ $vehicle->model }} | Rider: {{ $vehicle->assignedRider?->name ?? 'Unassigned' }}</p>
        </div>
        <a href="{{ route('admin.vehicles.show', $vehicle) }}" class="text-sm text-emerald-600 hover:underline">Back to Vehicle</a>
    </div>
    <div id="map" class="w-full" style="height: 600px; background: #e5e7eb;">
        <div class="flex items-center justify-center h-full text-gray-400">
            <div class="text-center">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <p class="text-lg">GPS Tracking Map</p>
                <p class="text-sm mt-1">Last known: {{ $vehicle->current_latitude ?? 'N/A' }}, {{ $vehicle->current_longitude ?? 'N/A' }}</p>
                <p class="text-sm">Updated: {{ $vehicle->last_location_update?->diffForHumans() ?? 'Never' }}</p>
                <p class="text-xs mt-4 text-gray-400">Configure a map provider (Google Maps, Leaflet, or Mapbox) to enable live tracking.</p>
            </div>
        </div>
    </div>
</div>
@endsection
