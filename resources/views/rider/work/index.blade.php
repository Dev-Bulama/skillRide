@extends('layouts.rider')
@section('title', 'Work Log')
@section('content')
<div class="space-y-4 pb-20">
    <h1 class="text-xl font-bold text-gray-800 dark:text-white">Work Log</h1>

    {{-- Clock In/Out --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm text-center">
        @if($isClockIn)
        <div class="w-20 h-20 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4"><div class="w-4 h-4 bg-green-500 rounded-full animate-pulse"></div></div>
        <p class="text-lg font-bold text-green-600">Currently Working</p>
        <p class="text-sm text-gray-500 mt-1">Since {{ $currentLog->clock_in_at->format('h:i A') }}</p>
        <form method="POST" action="{{ route('rider.work.clock-out') }}" class="mt-4">
            @csrf
            <input type="hidden" name="latitude" id="outLat"><input type="hidden" name="longitude" id="outLng">
            <button type="submit" onclick="getLocation('outLat','outLng')" class="w-full py-4 bg-red-500 text-white rounded-xl font-bold text-lg hover:bg-red-600 transition">Clock Out</button>
        </form>
        @else
        <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4"><div class="w-4 h-4 bg-gray-400 rounded-full"></div></div>
        <p class="text-lg font-bold text-gray-600 dark:text-gray-300">Not Clocked In</p>
        <form method="POST" action="{{ route('rider.work.clock-in') }}" class="mt-4">
            @csrf
            <input type="hidden" name="latitude" id="inLat"><input type="hidden" name="longitude" id="inLng">
            <button type="submit" onclick="getLocation('inLat','inLng')" class="w-full py-4 bg-emerald-500 text-white rounded-xl font-bold text-lg hover:bg-emerald-600 transition">Clock In</button>
        </form>
        @endif
    </div>

    {{-- Assistant --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-800 dark:text-white mb-3">Assistant</h3>
        @if($assistant && $assistant->assistant_name)
        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $assistant->assistant_name }} - {{ $assistant->assistant_phone }}</p>
        @else
        <form method="POST" action="{{ route('rider.work.assistant') }}" class="space-y-2">
            @csrf
            <input type="text" name="assistant_name" placeholder="Assistant Name" required class="block w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
            <input type="tel" name="assistant_phone" placeholder="Phone" required class="block w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
            <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600">Register Assistant</button>
        </form>
        @endif
    </div>

    {{-- History --}}
    <h3 class="font-semibold text-gray-800 dark:text-white">Work History</h3>
    <div class="space-y-2">
        @forelse($workLogs as $log)
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $log->clock_in_at->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $log->clock_in_at->format('h:i A') }} - {{ $log->clock_out_at ? $log->clock_out_at->format('h:i A') : 'Active' }}</p>
                </div>
                <span class="text-sm font-bold {{ $log->clock_out_at ? 'text-gray-800 dark:text-white' : 'text-green-600' }}">{{ $log->total_hours ? number_format($log->total_hours, 1) . 'h' : 'Active' }}</span>
            </div>
        </div>
        @empty
        <div class="text-center py-8 text-gray-400"><p>No work history.</p></div>
        @endforelse
    </div>
    <div>{{ $workLogs->links() }}</div>
</div>
@push('scripts')
<script>
function getLocation(latId, lngId) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(p) {
            document.getElementById(latId).value = p.coords.latitude;
            document.getElementById(lngId).value = p.coords.longitude;
        }, function() { alert('GPS required. Please enable location.'); });
    }
}
</script>
@endpush
@endsection
