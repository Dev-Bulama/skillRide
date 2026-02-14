@extends('layouts.rider')
@section('title', 'Dashboard')
@section('content')
<div class="space-y-4 pb-20">
    {{-- Welcome --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">Welcome, {{ $rider->name }}</h1>
            <p class="text-sm text-gray-500">{{ now()->format('l, M d Y') }}</p>
        </div>
        <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 font-bold">{{ substr($rider->name, 0, 1) }}</div>
    </div>

    {{-- Payment Countdown Card --}}
    <div class="bg-gradient-to-br from-emerald-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
        @if($nextPayment)
        <p class="text-sm opacity-80">Next Payment Due</p>
        <div class="flex items-center justify-between mt-2">
            <div>
                <p class="text-3xl font-bold">&#8358;{{ number_format($nextPayment->amount, 2) }}</p>
                <p class="text-sm opacity-80 mt-1" id="countdownText">{{ $nextPayment->due_date->format('M d, Y') }}</p>
            </div>
            <div class="text-center">
                <div id="countdown" class="text-4xl font-bold">
                    {{ max(0, now()->diffInDays($nextPayment->due_date, false)) }}
                </div>
                <p class="text-xs opacity-80">days left</p>
            </div>
        </div>
        @if($arrears > 0)
        <div class="mt-3 p-2 bg-red-500/20 rounded-lg">
            <p class="text-sm font-medium">Arrears: &#8358;{{ number_format($arrears, 2) }}</p>
        </div>
        @endif
        <a href="{{ route('rider.payments.index') }}" class="mt-4 block w-full text-center py-3 bg-white text-emerald-600 font-semibold rounded-xl hover:bg-gray-50 transition">Pay Now</a>
        @else
        <p class="text-lg font-medium">No pending payments</p>
        <p class="text-sm opacity-80 mt-1">You're all caught up!</p>
        @endif
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-gray-500">Total Paid</p>
            <p class="text-lg font-bold text-gray-800 dark:text-white">&#8358;{{ number_format($totalPaid, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-gray-500">This Month</p>
            <p class="text-lg font-bold text-gray-800 dark:text-white">&#8358;{{ number_format($thisMonthPaid, 2) }}</p>
        </div>
    </div>

    {{-- Vehicle Info --}}
    @if($vehicle)
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Assigned Vehicle</h3>
        <div class="flex items-center justify-between">
            <div>
                <p class="font-bold text-gray-800 dark:text-white">{{ $vehicle->registration_number }}</p>
                <p class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $vehicle->type)) }} - {{ $vehicle->make }} {{ $vehicle->model }}</p>
                <p class="text-xs text-gray-400 mt-1">Route: {{ $rider->riderProfile?->assignedRoute?->name ?? 'N/A' }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
    </div>
    @endif

    {{-- Clock In/Out --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Work Status</h3>
        @if($isClockIn)
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-emerald-600 font-medium">Currently Working</p>
                <p class="text-xs text-gray-500">Since {{ $currentWorkLog->clock_in_at->format('h:i A') }}</p>
            </div>
            <form method="POST" action="{{ route('rider.work.clock-out') }}">
                @csrf
                <input type="hidden" name="latitude" id="clockOutLat">
                <input type="hidden" name="longitude" id="clockOutLng">
                <button type="submit" onclick="getLocation('clockOutLat','clockOutLng')" class="px-6 py-3 bg-red-500 text-white rounded-xl font-semibold hover:bg-red-600 transition">Clock Out</button>
            </form>
        </div>
        @else
        <form method="POST" action="{{ route('rider.work.clock-in') }}">
            @csrf
            <input type="hidden" name="latitude" id="clockInLat">
            <input type="hidden" name="longitude" id="clockInLng">
            <button type="submit" onclick="getLocation('clockInLat','clockInLng')" class="w-full py-3 bg-emerald-500 text-white rounded-xl font-semibold hover:bg-emerald-600 transition">Clock In</button>
        </form>
        @endif
    </div>
</div>

@push('scripts')
<script>
function getLocation(latId, lngId) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(pos) {
            document.getElementById(latId).value = pos.coords.latitude;
            document.getElementById(lngId).value = pos.coords.longitude;
        }, function() {
            alert('GPS is required. Please enable location access.');
        });
    }
}
</script>
@endpush
@endsection
