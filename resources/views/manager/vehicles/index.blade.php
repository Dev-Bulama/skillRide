@extends('layouts.manager')

@section('title', 'Vehicles')
@section('header', 'Vehicle Management')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">Manage vehicles and rider assignments</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="relative">
                <input type="text" id="searchVehicles" placeholder="Search vehicles..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 w-64">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- Vehicles Table --}}
    @if(isset($vehicles) && $vehicles->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Make / Model</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Rider</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="vehicleTableBody">
                    @foreach($vehicles as $vehicle)
                    <tr class="hover:bg-gray-50 transition vehicle-row" data-search="{{ strtolower($vehicle->registration_number . ' ' . ($vehicle->make ?? '') . ' ' . ($vehicle->model ?? '') . ' ' . ($vehicle->assignedRider->name ?? '')) }}">
                        <td class="px-4 py-3">
                            <span class="text-sm font-semibold text-gray-800">{{ $vehicle->registration_number }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $typeLabels = [
                                    'keke_napep' => 'Keke Napep',
                                    'car' => 'Car',
                                    'bus' => 'Bus',
                                    'truck' => 'Truck',
                                ];
                                $typeColors = [
                                    'keke_napep' => 'bg-amber-100 text-amber-700',
                                    'car' => 'bg-blue-100 text-blue-700',
                                    'bus' => 'bg-purple-100 text-purple-700',
                                    'truck' => 'bg-indigo-100 text-indigo-700',
                                ];
                                $vehicleType = $vehicle->type ?? 'car';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeColors[$vehicleType] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $typeLabels[$vehicleType] ?? ucfirst(str_replace('_', ' ', $vehicleType)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm text-gray-800">{{ $vehicle->make ?? '' }} {{ $vehicle->model ?? '' }}</p>
                            @if($vehicle->year)
                                <p class="text-xs text-gray-400">{{ $vehicle->year }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($vehicle->assignedRider)
                                <div class="flex items-center space-x-2">
                                    <div class="w-7 h-7 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                        {{ substr($vehicle->assignedRider->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm text-gray-800">{{ $vehicle->assignedRider->name }}</span>
                                </div>
                            @else
                                <span class="text-sm text-gray-400 italic">Unassigned</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $status = $vehicle->status ?? 'unknown';
                                $statusStyles = [
                                    'active' => 'bg-emerald-100 text-emerald-700',
                                    'maintenance' => 'bg-yellow-100 text-yellow-700',
                                    'inactive' => 'bg-gray-100 text-gray-600',
                                    'decommissioned' => 'bg-red-100 text-red-700',
                                ];
                                $dotStyles = [
                                    'active' => 'bg-emerald-500',
                                    'maintenance' => 'bg-yellow-500',
                                    'inactive' => 'bg-gray-400',
                                    'decommissioned' => 'bg-red-500',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $statusStyles[$status] ?? 'bg-gray-100 text-gray-600' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $dotStyles[$status] ?? 'bg-gray-400' }}"></span>
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if($vehicle->assignedRider)
                                <form method="POST" action="{{ route('manager.vehicles.unassign', $vehicle) }}" class="inline" onsubmit="return confirm('Are you sure you want to unassign this rider from the vehicle?')">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 text-xs font-medium rounded-lg hover:bg-red-100 transition">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"/></svg>
                                        Unassign
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('manager.vehicles.assign', $vehicle) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-600 text-xs font-medium rounded-lg hover:bg-blue-100 transition">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                    Assign Rider
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($vehicles instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="mt-6">
        {{ $vehicles->links() }}
    </div>
    @endif
    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
        </svg>
        <h3 class="text-lg font-medium text-gray-600">No vehicles found</h3>
        <p class="text-sm text-gray-400 mt-1">Vehicles will appear here once they are added to the system.</p>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('searchVehicles').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.vehicle-row').forEach(function(row) {
            const searchData = row.getAttribute('data-search');
            row.style.display = searchData.includes(query) ? '' : 'none';
        });
    });
</script>
@endpush
