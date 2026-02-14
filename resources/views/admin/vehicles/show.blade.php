@extends('layouts.admin')

@section('title', 'Vehicle Details')
@section('header', 'Vehicle - ' . $vehicle->plate_number)

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.vehicles.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-emerald-600 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Vehicles
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.vehicles.track', $vehicle) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Track
            </a>
            <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white text-sm font-medium rounded-lg hover:bg-emerald-600 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vehicle?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Vehicle Card --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                {{-- Vehicle Image --}}
                <div class="mb-6">
                    @if($vehicle->image)
                        <img src="{{ asset('storage/' . $vehicle->image) }}" alt="{{ $vehicle->plate_number }}" class="w-full h-48 object-cover rounded-lg">
                    @else
                        <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Status Badge --}}
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">{{ $vehicle->make }} {{ $vehicle->model }}</h3>
                    @php
                        $statusColors = [
                            'active' => 'bg-emerald-100 text-emerald-800',
                            'inactive' => 'bg-gray-100 text-gray-800',
                            'maintenance' => 'bg-yellow-100 text-yellow-800',
                            'decommissioned' => 'bg-red-100 text-red-800',
                        ];
                        $color = $statusColors[$vehicle->status] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                        {{ ucfirst($vehicle->status) }}
                    </span>
                </div>

                {{-- Vehicle Details --}}
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Plate Number</span>
                        <span class="font-mono font-medium text-gray-900">{{ $vehicle->plate_number }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Type</span>
                        <span class="text-gray-900 capitalize">{{ $vehicle->type ?? 'Motorcycle' }}</span>
                    </div>
                    @if($vehicle->year)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Year</span>
                        <span class="text-gray-900">{{ $vehicle->year }}</span>
                    </div>
                    @endif
                    @if($vehicle->color)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Color</span>
                        <span class="text-gray-900">{{ $vehicle->color }}</span>
                    </div>
                    @endif
                    @if($vehicle->engine_number)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Engine No.</span>
                        <span class="font-mono text-gray-900">{{ $vehicle->engine_number }}</span>
                    </div>
                    @endif
                    @if($vehicle->chassis_number)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Chassis No.</span>
                        <span class="font-mono text-gray-900">{{ $vehicle->chassis_number }}</span>
                    </div>
                    @endif
                    @if($vehicle->daily_rate)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Daily Rate</span>
                        <span class="font-medium text-emerald-600">KES {{ number_format($vehicle->daily_rate, 2) }}</span>
                    </div>
                    @endif
                </div>

                {{-- QR Code --}}
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Vehicle QR Code</h4>
                    <div class="flex items-center justify-center p-4 bg-gray-50 rounded-lg">
                        @if($vehicle->qr_code)
                            <img src="{{ asset('storage/' . $vehicle->qr_code) }}" alt="QR Code for {{ $vehicle->plate_number }}" class="w-32 h-32">
                        @else
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                </svg>
                                <p class="text-xs text-gray-400">No QR code generated</p>
                                <form action="{{ route('admin.vehicles.generate-qr', $vehicle) }}" method="POST" class="mt-2">
                                    @csrf
                                    <button type="submit" class="text-xs text-emerald-600 hover:text-emerald-700 font-medium">Generate QR Code</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Assigned Rider --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Assigned Rider</h3>
                @if($vehicle->rider)
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 font-bold text-lg">
                            {{ strtoupper(substr($vehicle->rider->name, 0, 2)) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="text-base font-medium text-gray-900">{{ $vehicle->rider->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $vehicle->rider->phone ?? $vehicle->rider->email }}</p>
                            @if($vehicle->rider->id_number)
                                <p class="text-xs text-gray-400">ID: {{ $vehicle->rider->id_number }}</p>
                            @endif
                        </div>
                        <a href="{{ route('admin.riders.show', $vehicle->rider) }}" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-700 text-sm font-medium rounded-lg hover:bg-emerald-100 transition-colors">
                            View Profile
                        </a>
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <p class="text-sm text-gray-500 mb-2">No rider assigned</p>
                        <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Assign a rider</a>
                    </div>
                @endif
            </div>

            {{-- Insurance Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Insurance & Registration</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Registration Date</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ $vehicle->registration_date ? $vehicle->registration_date->format('M d, Y') : 'Not set' }}
                        </p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Insurance Expiry</p>
                        <p class="text-sm font-medium {{ $vehicle->insurance_expiry && $vehicle->insurance_expiry->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $vehicle->insurance_expiry ? $vehicle->insurance_expiry->format('M d, Y') : 'Not set' }}
                            @if($vehicle->insurance_expiry && $vehicle->insurance_expiry->isPast())
                                <span class="text-xs text-red-500 ml-1">(Expired)</span>
                            @endif
                        </p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Insurance Provider</p>
                        <p class="text-sm font-medium text-gray-900">{{ $vehicle->insurance_provider ?? 'Not set' }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Policy Number</p>
                        <p class="text-sm font-mono font-medium text-gray-900">{{ $vehicle->insurance_policy_number ?? 'Not set' }}</p>
                    </div>
                </div>
            </div>

            {{-- Maintenance History --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Maintenance History</h3>
                </div>
                @if($vehicle->maintenanceRecords && $vehicle->maintenanceRecords->count())
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($vehicle->maintenanceRecords as $record)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $record->date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 capitalize">{{ $record->type }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $record->description }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">KES {{ number_format($record->cost, 2) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $record->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($record->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-6 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="text-sm">No maintenance records found</p>
                    </div>
                @endif
            </div>

            {{-- Notes --}}
            @if($vehicle->notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Notes</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $vehicle->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
