@extends('layouts.manager')

@section('title', ($rider->user->name ?? 'Rider') . ' - Profile')
@section('header', 'Rider Profile')

@section('content')
<div class="space-y-6">
    {{-- Back Button --}}
    <a href="{{ route('manager.riders.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Riders
    </a>

    {{-- Rider Profile Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-32 relative">
            <div class="absolute -bottom-12 left-6">
                @if($rider->profile_photo)
                    <img src="{{ Storage::url($rider->profile_photo) }}" alt="{{ $rider->user->name ?? 'Rider' }}" class="w-24 h-24 rounded-xl object-cover border-4 border-white shadow-lg">
                @else
                    <div class="w-24 h-24 bg-blue-700 rounded-xl border-4 border-white shadow-lg flex items-center justify-center text-white text-2xl font-bold">
                        {{ substr($rider->user->name ?? 'R', 0, 1) }}
                    </div>
                @endif
            </div>
        </div>
        <div class="pt-16 pb-6 px-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $rider->user->name ?? 'Unknown Rider' }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $rider->user->email ?? '' }}</p>
                    @if($rider->user->phone ?? null)
                        <p class="text-sm text-gray-500">{{ $rider->user->phone }}</p>
                    @endif
                </div>
                <div class="mt-3 sm:mt-0 flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full
                        {{ ($rider->status ?? 'inactive') === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($rider->status ?? 'Inactive') }}
                    </span>
                    <a href="{{ route('manager.riders.payment-status', $rider->id) }}" class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition">
                        Payment Status
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Rider Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Personal Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Full Name</label>
                        <p class="text-sm font-medium text-gray-800 mt-1">{{ $rider->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Email</label>
                        <p class="text-sm font-medium text-gray-800 mt-1">{{ $rider->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Phone</label>
                        <p class="text-sm font-medium text-gray-800 mt-1">{{ $rider->user->phone ?? $rider->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">ID Number</label>
                        <p class="text-sm font-medium text-gray-800 mt-1">{{ $rider->id_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">License Number</label>
                        <p class="text-sm font-medium text-gray-800 mt-1">{{ $rider->license_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Joined</label>
                        <p class="text-sm font-medium text-gray-800 mt-1">{{ $rider->created_at ? $rider->created_at->format('M d, Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            {{-- Vehicle Assignment --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Vehicle Assignment</h3>
                @if($rider->vehicle)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Registration</label>
                        <p class="text-sm font-medium text-gray-800 mt-1">{{ $rider->vehicle->registration_number ?? $rider->vehicle->plate_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Make / Model</label>
                        <p class="text-sm font-medium text-gray-800 mt-1">{{ $rider->vehicle->make ?? '' }} {{ $rider->vehicle->model ?? '' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Color</label>
                        <p class="text-sm font-medium text-gray-800 mt-1">{{ $rider->vehicle->color ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Status</label>
                        <p class="text-sm font-medium mt-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                {{ ($rider->vehicle->status ?? '') === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($rider->vehicle->status ?? 'Unknown') }}
                            </span>
                        </p>
                    </div>
                </div>
                @else
                <p class="text-sm text-gray-400">No vehicle assigned</p>
                @endif
            </div>
        </div>

        {{-- Payment Summary Sidebar --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Summary</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-lg">
                        <span class="text-sm text-emerald-700">Total Paid</span>
                        <span class="text-lg font-bold text-emerald-700">R{{ number_format($rider->total_paid ?? 0, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <span class="text-sm text-red-700">Arrears</span>
                        <span class="text-lg font-bold text-red-700">R{{ number_format($rider->arrears ?? 0, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <span class="text-sm text-blue-700">This Month</span>
                        <span class="text-lg font-bold text-blue-700">R{{ number_format($rider->this_month_paid ?? 0, 2) }}</span>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('manager.riders.payment-status', $rider->id) }}" class="block w-full text-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition">
                        View Full Payment Details
                    </a>
                </div>
            </div>

            {{-- Route Info --}}
            @if($rider->route ?? null)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Route</h3>
                <div class="space-y-2">
                    <p class="text-sm font-medium text-gray-800">{{ $rider->route->name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500">{{ $rider->route->description ?? '' }}</p>
                    @if($rider->route->id ?? null)
                        <a href="{{ route('manager.routes.show', $rider->route->id) }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 font-medium mt-2">
                            View Route
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
