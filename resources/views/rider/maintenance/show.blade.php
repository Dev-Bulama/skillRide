@extends('layouts.rider')
@section('title', 'Maintenance Detail')
@section('content')
<div class="space-y-4 pb-20">
    <a href="{{ route('rider.maintenance.index') }}" class="text-sm text-emerald-600 hover:underline">&larr; Back</a>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white">{{ ucfirst($record->type) }}</h2>
            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $record->status === 'completed' ? 'bg-green-100 text-green-800' : ($record->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">{{ ucfirst($record->status) }}</span>
        </div>
        <p class="text-gray-600 dark:text-gray-300">{{ $record->description }}</p>
        <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
            <div><span class="text-gray-500">Priority:</span> <span class="font-medium {{ $record->priority === 'critical' ? 'text-red-600' : ($record->priority === 'high' ? 'text-orange-600' : 'text-gray-800 dark:text-white') }}">{{ ucfirst($record->priority) }}</span></div>
            <div><span class="text-gray-500">Vehicle:</span> <span class="font-medium text-gray-800 dark:text-white">{{ $record->vehicle?->registration_number ?? 'N/A' }}</span></div>
            <div><span class="text-gray-500">Reported:</span> <span class="font-medium text-gray-800 dark:text-white">{{ $record->created_at->format('M d, Y') }}</span></div>
            @if($record->cost)<div><span class="text-gray-500">Cost:</span> <span class="font-medium text-gray-800 dark:text-white">&#8358;{{ number_format($record->cost, 2) }}</span></div>@endif
        </div>
    </div>
    @if($record->media && $record->media->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-800 dark:text-white mb-3">Attachments</h3>
        <div class="grid grid-cols-2 gap-2">
            @foreach($record->media as $media)
            <div class="rounded-lg overflow-hidden bg-gray-100">
                @if($media->file_type === 'image')
                <img src="{{ Storage::url($media->file_path) }}" alt="{{ $media->original_name }}" class="w-full h-32 object-cover">
                @else
                <div class="p-4 text-center"><svg class="w-8 h-8 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg><p class="text-xs text-gray-500 mt-1">{{ $media->original_name }}</p></div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
