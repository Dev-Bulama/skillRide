<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Verification') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-6">
            <a href="/" class="inline-flex items-center space-x-2">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-xl flex items-center justify-center">
                    <span class="text-white font-bold text-lg">SR</span>
                </div>
                <span class="text-2xl font-bold text-gray-800">SkillRide</span>
            </a>
        </div>

        {{-- Card Content --}}
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            @yield('content')
        </div>

        {{-- Footer --}}
        <div class="text-center mt-6">
            <p class="text-xs text-gray-400">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
