<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#059669">
    <title>@yield('title', 'Rider Dashboard') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .bottom-safe-area { padding-bottom: env(safe-area-inset-bottom, 0px); }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="flex flex-col h-screen">
        {{-- Top Bar --}}
        <header class="bg-white shadow-sm border-b border-gray-200 z-20 flex-shrink-0">
            <div class="flex items-center justify-between h-14 px-4">
                {{-- Logo --}}
                <a href="{{ route('rider.dashboard') }}" class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">SR</span>
                    </div>
                    <span class="text-lg font-bold text-gray-800">SkillRide</span>
                </a>

                <div class="flex items-center space-x-3">
                    {{-- Notifications --}}
                    <button class="relative p-2 text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    {{-- Profile --}}
                    <a href="{{ route('rider.profile') }}" class="flex items-center">
                        @if(auth()->user()->rider && auth()->user()->rider->profile_photo)
                            <img src="{{ Storage::url(auth()->user()->rider->profile_photo) }}" alt="Profile" class="w-8 h-8 rounded-full object-cover border-2 border-emerald-500">
                        @else
                            <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                    </a>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="mx-4 mt-3 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        </div>
        @endif
        @if(session('error'))
        <div class="mx-4 mt-3 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        </div>
        @endif

        {{-- Main Content (scrollable area between top bar and bottom nav) --}}
        <main class="flex-1 overflow-y-auto pb-20">
            @yield('content')
        </main>

        {{-- Bottom Navigation --}}
        <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-20 bottom-safe-area">
            <div class="flex items-center justify-around h-16 max-w-lg mx-auto">
                {{-- Dashboard --}}
                <a href="{{ route('rider.dashboard') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('rider.dashboard') ? 'text-emerald-600' : 'text-gray-400' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="text-xs mt-1 font-medium">Home</span>
                </a>

                {{-- Payments --}}
                <a href="{{ route('rider.payments.index') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('rider.payments.*') ? 'text-emerald-600' : 'text-gray-400' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs mt-1 font-medium">Payments</span>
                </a>

                {{-- Maintenance --}}
                <a href="{{ route('rider.maintenance.index') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('rider.maintenance.*') ? 'text-emerald-600' : 'text-gray-400' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="text-xs mt-1 font-medium">Maintain</span>
                </a>

                {{-- Work --}}
                <a href="{{ route('rider.work.index') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('rider.work.*') ? 'text-emerald-600' : 'text-gray-400' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs mt-1 font-medium">Work</span>
                </a>

                {{-- QR Code --}}
                <a href="{{ route('rider.qr.index') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('rider.qr.*') ? 'text-emerald-600' : 'text-gray-400' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    <span class="text-xs mt-1 font-medium">QR Code</span>
                </a>
            </div>
        </nav>
    </div>

    {{-- GPS Permission Check --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if ('geolocation' in navigator) {
                navigator.permissions.query({ name: 'geolocation' }).then(function(result) {
                    if (result.state === 'prompt') {
                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                console.log('GPS permission granted');
                                updateLocation(position.coords.latitude, position.coords.longitude);
                            },
                            function(error) {
                                console.warn('GPS permission denied:', error.message);
                            },
                            { enableHighAccuracy: true }
                        );
                    } else if (result.state === 'granted') {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            updateLocation(position.coords.latitude, position.coords.longitude);
                        }, function() {}, { enableHighAccuracy: true });
                    }
                });
            }

            function updateLocation(lat, lng) {
                fetch('/rider/location/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ latitude: lat, longitude: lng })
                }).catch(function(err) {
                    console.log('Location update skipped:', err.message);
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
