@extends('layouts.manager')

@section('title', 'My Riders')
@section('header', 'My Riders')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">Manage and monitor your assigned riders</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="relative">
                <input type="text" id="searchRiders" placeholder="Search riders..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 w-64">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- Rider Cards Grid --}}
    @if(isset($riders) && $riders->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="riderGrid">
        @foreach($riders as $rider)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow rider-card" data-name="{{ strtolower($rider->name ?? '') }}">
            <div class="p-5">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-3">
                        @if($rider->avatar)
                            <img src="{{ Storage::url($rider->avatar) }}" alt="{{ $rider->name }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ substr($rider->name ?? 'R', 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <h4 class="text-sm font-semibold text-gray-800">{{ $rider->name }}</h4>
                            <p class="text-xs text-gray-500">{{ $rider->phone ?? $rider->email ?? '' }}</p>
                        </div>
                    </div>
                    {{-- Payment Status Badge --}}
                    @php
                        $paymentStatus = $rider->payment_status ?? 'unknown';
                    @endphp
                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                        {{ $paymentStatus === 'paid' ? 'bg-emerald-100 text-emerald-700' : ($paymentStatus === 'overdue' ? 'bg-red-100 text-red-700' : ($paymentStatus === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600')) }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-1.5
                            {{ $paymentStatus === 'paid' ? 'bg-emerald-500' : ($paymentStatus === 'overdue' ? 'bg-red-500' : ($paymentStatus === 'pending' ? 'bg-yellow-500' : 'bg-gray-400')) }}"></span>
                        {{ ucfirst($paymentStatus) }}
                    </span>
                </div>

                {{-- Vehicle Info --}}
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Vehicle</span>
                        <span class="font-medium text-gray-800">{{ $rider->assignedVehicle->registration_number ?? 'Not assigned' }}</span>
                    </div>
                    @if($rider->riderProfile && $rider->riderProfile->assignedRoute)
                    <div class="flex items-center justify-between text-sm mt-1">
                        <span class="text-gray-500">Route</span>
                        <span class="font-medium text-gray-800">{{ $rider->riderProfile->assignedRoute->name ?? 'N/A' }}</span>
                    </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="mt-4 flex items-center space-x-2">
                    <a href="{{ route('manager.riders.show', $rider->id) }}" class="flex-1 text-center px-3 py-2 bg-blue-50 text-blue-600 text-sm font-medium rounded-lg hover:bg-blue-100 transition">
                        View Profile
                    </a>
                    <a href="{{ route('manager.riders.payment-status', $rider->id) }}" class="flex-1 text-center px-3 py-2 bg-gray-50 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-100 transition">
                        Payment Status
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($riders instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="mt-6">
        {{ $riders->links() }}
    </div>
    @endif
    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        <h3 class="text-lg font-medium text-gray-600">No riders assigned</h3>
        <p class="text-sm text-gray-400 mt-1">Riders will appear here once assigned to your management.</p>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('searchRiders').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.rider-card').forEach(function(card) {
            const name = card.getAttribute('data-name');
            card.style.display = name.includes(query) ? '' : 'none';
        });
    });
</script>
@endpush
