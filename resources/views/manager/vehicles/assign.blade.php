@extends('layouts.manager')

@section('title', 'Assign Rider')
@section('header', 'Assign Rider to Vehicle')

@section('content')
<div class="space-y-6">
    {{-- Back Link --}}
    <a href="{{ route('manager.vehicles.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Vehicles
    </a>

    {{-- Vehicle Information Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-emerald-500 px-6 py-4">
            <h3 class="text-lg font-semibold text-white">Vehicle Details</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Registration Number</label>
                    <p class="text-sm font-semibold text-gray-800 mt-1">{{ $vehicle->registration_number }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Type</label>
                    <p class="text-sm font-medium text-gray-800 mt-1">
                        @php
                            $typeLabels = [
                                'keke_napep' => 'Keke Napep',
                                'car' => 'Car',
                                'bus' => 'Bus',
                                'truck' => 'Truck',
                            ];
                            $vehicleType = $vehicle->type ?? 'car';
                        @endphp
                        {{ $typeLabels[$vehicleType] ?? ucfirst(str_replace('_', ' ', $vehicleType)) }}
                    </p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Make / Model</label>
                    <p class="text-sm font-medium text-gray-800 mt-1">{{ $vehicle->make ?? '' }} {{ $vehicle->model ?? '' }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Status</label>
                    <p class="text-sm font-medium mt-1">
                        @php
                            $status = $vehicle->status ?? 'unknown';
                            $statusStyles = [
                                'active' => 'bg-emerald-100 text-emerald-700',
                                'maintenance' => 'bg-yellow-100 text-yellow-700',
                                'inactive' => 'bg-gray-100 text-gray-600',
                                'decommissioned' => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusStyles[$status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($status) }}
                        </span>
                    </p>
                </div>
            </div>
            @if($vehicle->color || $vehicle->year)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-4 pt-4 border-t border-gray-100">
                @if($vehicle->year)
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Year</label>
                    <p class="text-sm font-medium text-gray-800 mt-1">{{ $vehicle->year }}</p>
                </div>
                @endif
                @if($vehicle->color)
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Color</label>
                    <p class="text-sm font-medium text-gray-800 mt-1">{{ $vehicle->color }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- Assign Rider Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Assign a Rider</h3>
            <p class="text-sm text-gray-500 mt-1">Select a rider from the list of available riders to assign to this vehicle.</p>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('manager.vehicles.assign.store', $vehicle) }}">
                @csrf

                <div class="max-w-lg">
                    <label for="rider_id" class="block text-sm font-medium text-gray-700 mb-2">Select Rider</label>
                    <select name="rider_id" id="rider_id" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm @error('rider_id') border-red-300 ring-red-500 @enderror">
                        <option value="">-- Choose a rider --</option>
                        @forelse($availableRiders as $rider)
                            <option value="{{ $rider->id }}" {{ old('rider_id') == $rider->id ? 'selected' : '' }}>
                                {{ $rider->name }} ({{ $rider->email }})
                            </option>
                        @empty
                            <option value="" disabled>No available riders</option>
                        @endforelse
                    </select>
                    @error('rider_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex items-center space-x-3">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition"
                        {{ isset($availableRiders) && $availableRiders->isEmpty() ? 'disabled' : '' }}>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        Assign Rider
                    </button>
                    <a href="{{ route('manager.vehicles.index') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
