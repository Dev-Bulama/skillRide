@extends('layouts.manager')
@section('title', 'Live Tracking')
@section('header', 'Live Vehicle Tracking')
@section('content')
<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Active Vehicles</h3>
            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-medium rounded-full">{{ $vehicles->count() }} online</span>
        </div>
        <div id="map" class="w-full h-96 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
            <div class="text-center text-gray-400">
                <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <p class="text-sm">Map integration ready</p>
                <p class="text-xs mt-1">Add Google Maps or Leaflet API key to enable</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Vehicle Positions</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rider</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Coordinates</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Speed</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($vehicles as $vehicle)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-3">
                        <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $vehicle->registration_number }}</p>
                        <p class="text-xs text-gray-500">{{ $vehicle->make }} {{ $vehicle->model }}</p>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                        @if($vehicle->assignedRider)
                            {{ $vehicle->assignedRider->name }}
                            <span class="block text-xs text-gray-400">{{ $vehicle->assignedRider->phone }}</span>
                        @else
                            <span class="text-gray-400">Unassigned</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                        @if($vehicle->current_latitude && $vehicle->current_longitude)
                            {{ number_format($vehicle->current_latitude, 4) }}, {{ number_format($vehicle->current_longitude, 4) }}
                        @else
                            <span class="text-gray-400">No GPS data</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                        @if($vehicle->current_speed){{ number_format($vehicle->current_speed, 1) }} km/h @else - @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-1 text-xs rounded-full {{ $vehicle->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $vehicle->status === 'active' ? 'bg-green-500 animate-pulse' : 'bg-gray-400' }}"></span>
                            {{ ucfirst($vehicle->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No active vehicles to track.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    // Vehicle data for map integration
    const vehicles = @json($vehicles->map(fn($v) => ['id' => $v->id, 'reg' => $v->registration_number, 'lat' => $v->current_latitude, 'lng' => $v->current_longitude, 'rider' => $v->assignedRider?->name]));

    // Auto-refresh every 30 seconds
    setTimeout(() => window.location.reload(), 30000);
</script>
@endpush
@endsection
