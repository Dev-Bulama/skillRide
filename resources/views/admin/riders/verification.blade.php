@extends('layouts.admin')
@section('title', 'Rider Verification')
@section('header', 'Pending Verifications')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">{{ $pendingRiders->count() }} rider(s) awaiting verification</p>
    </div>

    @if($pendingRiders->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($pendingRiders as $rider)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                        {{ strtoupper(substr($rider->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-base font-semibold text-gray-900 dark:text-white truncate">{{ $rider->name }}</h4>
                        <p class="text-sm text-gray-500 truncate">{{ $rider->email }}</p>
                        <p class="text-xs text-gray-400">Applied {{ $rider->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ $rider->phone ?? 'No phone' }}
                    </div>
                    @if($rider->riderProfile && $rider->riderProfile->assignedVehicle)
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        {{ $rider->riderProfile->assignedVehicle->registration_number }}
                    </div>
                    @endif
                </div>

                {{-- Document Status Checklist --}}
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mb-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Documents</p>
                    <div class="space-y-1">
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2 {{ ($rider->riderProfile->id_document ?? false) ? 'text-emerald-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-gray-600 dark:text-gray-300">ID Document</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2 {{ ($rider->riderProfile->license_document ?? false) ? 'text-emerald-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-gray-600 dark:text-gray-300">Driver's License</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2 {{ ($rider->riderProfile->profile_photo ?? false) ? 'text-emerald-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-gray-600 dark:text-gray-300">Profile Photo</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <a href="{{ route('admin.riders.documents', $rider) }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Review Docs</a>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('admin.riders.reject', $rider) }}" class="inline" onsubmit="return confirm('Reject this rider?')">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200">Reject</button>
                    </form>
                    <form method="POST" action="{{ route('admin.riders.approve', $rider) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 bg-emerald-500 text-white text-sm font-medium rounded-lg hover:bg-emerald-600">Approve</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-12 text-center">
        <svg class="w-16 h-16 mx-auto text-emerald-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        <h3 class="text-lg font-semibold text-gray-700 dark:text-white">All Caught Up!</h3>
        <p class="text-sm text-gray-500 mt-1">No pending verifications at the moment.</p>
    </div>
    @endif
</div>
@endsection
