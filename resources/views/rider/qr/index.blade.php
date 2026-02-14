@extends('layouts.rider')
@section('title', 'QR Code')
@section('content')
<div class="space-y-4 pb-20">
    <h1 class="text-xl font-bold text-gray-800 dark:text-white text-center">Your QR Code</h1>
    @if($vehicle && $qrCode)
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm text-center">
        <div class="inline-block p-4 bg-white rounded-xl shadow-inner">{!! $qrCode !!}</div>
        <p class="mt-4 text-sm text-gray-500">Scan to verify rider and vehicle</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-800 dark:text-white mb-2">Vehicle Details</h3>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">Registration:</span><span class="font-medium text-gray-800 dark:text-white">{{ $vehicle->registration_number }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Type:</span><span class="font-medium text-gray-800 dark:text-white">{{ ucfirst(str_replace('_', ' ', $vehicle->type)) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Make/Model:</span><span class="font-medium text-gray-800 dark:text-white">{{ $vehicle->make }} {{ $vehicle->model }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Color:</span><span class="font-medium text-gray-800 dark:text-white">{{ $vehicle->color }}</span></div>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <button onclick="window.print()" class="py-3 bg-blue-500 text-white rounded-xl font-medium hover:bg-blue-600 transition">Print QR</button>
        <form method="POST" action="{{ route('rider.qr.generate') }}">@csrf<button type="submit" class="w-full py-3 bg-emerald-500 text-white rounded-xl font-medium hover:bg-emerald-600 transition">Regenerate</button></form>
    </div>
    @else
    <div class="text-center py-12 text-gray-400">
        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
        <p>No vehicle assigned yet.</p>
        <p class="text-sm mt-1">Contact your manager to get a vehicle assignment.</p>
    </div>
    @endif
</div>
@endsection
