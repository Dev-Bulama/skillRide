@extends('layouts.manager')
@section('title', 'Maintenance Detail')
@section('header', 'Maintenance Record')
@section('content')
<div class="space-y-6">
    <a href="{{ route('manager.maintenance.index') }}" class="text-sm text-blue-600 hover:text-blue-700">&larr; Back to Maintenance</a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Details</h3>
                    <div class="flex gap-2">
                        <span class="px-2 py-1 text-xs rounded-full {{ $record->priority === 'critical' ? 'bg-red-100 text-red-800' : ($record->priority === 'high' ? 'bg-orange-100 text-orange-800' : ($record->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">{{ ucfirst($record->priority) }}</span>
                        <span class="px-2 py-1 text-xs rounded-full {{ $record->status === 'completed' ? 'bg-green-100 text-green-800' : ($record->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst(str_replace('_', ' ', $record->status)) }}</span>
                    </div>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Type:</span><span class="text-gray-800 dark:text-white">{{ ucfirst(str_replace('_', ' ', $record->type)) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Vehicle:</span><span class="text-gray-800 dark:text-white">{{ $record->vehicle->registration_number ?? 'N/A' }} ({{ $record->vehicle->make ?? '' }} {{ $record->vehicle->model ?? '' }})</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Reported By:</span><span class="text-gray-800 dark:text-white">{{ $record->reportedBy->name ?? 'N/A' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Created:</span><span class="text-gray-800 dark:text-white">{{ $record->created_at->format('M d, Y h:i A') }}</span></div>
                    @if($record->cost)<div class="flex justify-between"><span class="text-gray-500">Est. Cost:</span><span class="text-gray-800 dark:text-white font-semibold">N{{ number_format($record->cost, 2) }}</span></div>@endif
                    @if($record->scheduled_date)<div class="flex justify-between"><span class="text-gray-500">Scheduled:</span><span class="text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($record->scheduled_date)->format('M d, Y') }}</span></div>@endif
                    @if($record->completed_date)<div class="flex justify-between"><span class="text-gray-500">Completed:</span><span class="text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($record->completed_date)->format('M d, Y') }}</span></div>@endif
                </div>
                @if($record->description)
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $record->description }}</p>
                </div>
                @endif
                @if($record->notes)
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $record->notes }}</p>
                </div>
                @endif
            </div>

            @if($record->media && $record->media->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Media ({{ $record->media->count() }})</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($record->media as $media)
                    <div class="relative group">
                        @if($media->file_type === 'image')
                        <img src="{{ asset('storage/' . $media->file_path) }}" alt="Maintenance photo" class="w-full h-32 object-cover rounded-lg">
                        @else
                        <div class="w-full h-32 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        @endif
                        <p class="text-xs text-gray-500 mt-1">{{ $media->uploadedBy->name ?? 'Unknown' }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            @if($record->status === 'pending' || $record->status === 'in_progress')
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-3">Actions</h3>
                <form method="POST" action="{{ route('manager.maintenance.approve', $record) }}" onsubmit="return confirm('Mark this maintenance record as completed?')">
                    @csrf
                    <button type="submit" class="w-full py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600">Approve / Complete</button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
