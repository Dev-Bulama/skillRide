@extends('layouts.public')
@section('title', 'Vehicle Verification')
@section('content')
@if($valid && $rider && $vehicle)
<div class="text-center mb-6">
    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-3"><svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></div>
    <h2 class="text-2xl font-bold text-green-700">Verified</h2>
    <p class="text-sm text-gray-500">This rider and vehicle are registered with SkillRide</p>
</div>
<div class="space-y-4">
    <div class="bg-gray-50 rounded-xl p-4">
        <h3 class="text-xs font-semibold text-gray-400 uppercase mb-2">Rider Information</h3>
        <p class="text-lg font-bold text-gray-800">{{ $rider->name }}</p>
        <p class="text-sm text-gray-600">Phone: {{ $rider->phone }}</p>
        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full mt-2 {{ $rider->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($rider->status) }}</span>
    </div>
    <div class="bg-gray-50 rounded-xl p-4">
        <h3 class="text-xs font-semibold text-gray-400 uppercase mb-2">Vehicle Information</h3>
        <p class="text-lg font-bold text-gray-800">{{ $vehicle->registration_number }}</p>
        <p class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $vehicle->type)) }} - {{ $vehicle->make }} {{ $vehicle->model }}</p>
        <p class="text-sm text-gray-600">Color: {{ $vehicle->color ?? 'N/A' }}</p>
    </div>
    @if($route)
    <div class="bg-gray-50 rounded-xl p-4">
        <h3 class="text-xs font-semibold text-gray-400 uppercase mb-2">Route</h3>
        <p class="font-medium text-gray-800">{{ $route->name }}</p>
        <p class="text-sm text-gray-600">{{ $route->start_location }} → {{ $route->end_location }}</p>
    </div>
    @endif
</div>
@else
<div class="text-center">
    <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-3"><svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></div>
    <h2 class="text-2xl font-bold text-red-700">Not Found</h2>
    <p class="text-sm text-gray-500 mt-2">This vehicle or rider could not be verified.</p>
</div>
@endif
<div class="mt-8 text-center text-xs text-gray-400">
    <p>Powered by SkillRide Fleet Management</p>
    <p>Verified at {{ now()->format('M d, Y h:i A') }}</p>
</div>
@endsection
