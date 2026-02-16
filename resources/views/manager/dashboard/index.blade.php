@extends('layouts.manager')

@section('title', 'Manager Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
        <h2 class="text-2xl font-bold">Welcome back, {{ $manager->name }}</h2>
        <p class="mt-1 text-blue-100">Here's an overview of your fleet operations today.</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Assigned Riders --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Assigned Riders</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $assignedRidersCount ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- Active Vehicles --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Vehicles</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $activeVehiclesCount ?? 0 }}<span class="text-sm text-gray-400 font-normal">/{{ $totalVehiclesCount ?? 0 }}</span></p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10m10 0h4m-4 0H9m-5 0H3m2 0h1"/></svg>
                </div>
            </div>
        </div>

        {{-- Monthly Collections --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Monthly Collections</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">&#8358;{{ number_format($totalPaymentsThisMonth ?? 0, 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- Pending Maintenance --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pending Maintenance</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $maintenanceVehiclesCount ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerts Section --}}
    @if(isset($alerts) && $alerts->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            Alerts
            <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 text-xs font-semibold rounded-full">{{ $alerts->count() }}</span>
        </h3>
        <div class="space-y-3">
            @foreach($alerts as $alert)
            @php $alertType = $alert['type'] ?? 'info'; $alertMessage = $alert['message'] ?? ''; $alertUrl = $alert['action_url'] ?? '#'; @endphp
            <a href="{{ $alertUrl }}" class="block flex items-start p-3 rounded-lg {{ $alertType === 'danger' ? 'bg-red-50 border border-red-200' : ($alertType === 'warning' ? 'bg-yellow-50 border border-yellow-200' : 'bg-blue-50 border border-blue-200') }}">
                <div class="flex-shrink-0 mt-0.5">
                    @if($alertType === 'danger')
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @elseif($alertType === 'warning')
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    @else
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @endif
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium {{ $alertType === 'danger' ? 'text-red-800' : ($alertType === 'warning' ? 'text-yellow-800' : 'text-blue-800') }}">{{ $alertMessage }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Rider Payment Status --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Rider Payment Status</h3>
                @if(isset($overduePaymentsCount) && $overduePaymentsCount > 0)
                    <span class="px-2.5 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">{{ $overduePaymentsCount }} Overdue</span>
                @endif
            </div>

            @if(isset($recentPayments) && $recentPayments->count() > 0)
            <div class="space-y-3">
                @foreach($recentPayments as $payment)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm
                            {{ $payment->status === 'paid' ? 'bg-emerald-500' : ($payment->status === 'overdue' ? 'bg-red-500' : 'bg-yellow-500') }}">
                            {{ substr($payment->rider->name ?? 'R', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $payment->rider->name ?? 'Unknown Rider' }}</p>
                            <p class="text-xs text-gray-500">
                                @if($payment->status === 'overdue')
                                    Overdue by {{ $payment->due_date ? now()->diffInDays($payment->due_date) : '?' }} days
                                @elseif($payment->status === 'paid')
                                    Paid {{ $payment->paid_at ? $payment->paid_at->diffForHumans() : '' }}
                                @else
                                    Due {{ $payment->due_date ? $payment->due_date->format('M d, Y') : 'N/A' }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-800">&#8358;{{ number_format($payment->amount ?? 0, 2) }}</p>
                        <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full
                            {{ $payment->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : ($payment->status === 'overdue' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                <a href="{{ route('manager.payments.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View all payments &rarr;</a>
            </div>
            @else
            <div class="text-center py-8 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <p class="text-sm">No recent payments</p>
            </div>
            @endif
        </div>

        {{-- Managed Vehicles --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Managed Vehicles</h3>
                <a href="{{ route('manager.routes.track') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Track All</a>
            </div>

            @if(isset($managedVehicles) && $managedVehicles->count() > 0)
            <div class="space-y-3">
                @foreach($managedVehicles->take(6) as $vehicle)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10m10 0h4m-4 0H9m-5 0H3m2 0h1"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $vehicle->registration_number ?? $vehicle->plate_number ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $vehicle->make ?? '' }} {{ $vehicle->model ?? '' }}</p>
                        </div>
                    </div>
                    <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full
                        {{ $vehicle->status === 'active' ? 'bg-emerald-100 text-emerald-700' : ($vehicle->status === 'maintenance' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700') }}">
                        {{ ucfirst($vehicle->status ?? 'Unknown') }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <p class="text-sm">No vehicles assigned</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
