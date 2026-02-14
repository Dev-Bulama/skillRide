@extends('layouts.rider')
@section('title', 'Maintenance')
@section('content')
<div class="space-y-4 pb-20">
    <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold text-gray-800 dark:text-white">Maintenance</h1>
        <a href="{{ route('rider.maintenance.create') }}" class="px-4 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600">Report Issue</a>
    </div>
    @forelse($records as $record)
    <a href="{{ route('rider.maintenance.show', $record) }}" class="block bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="font-semibold text-gray-800 dark:text-white">{{ ucfirst($record->type) }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($record->description, 60) }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $record->created_at->format('M d, Y') }} | Vehicle: {{ $record->vehicle?->registration_number ?? 'N/A' }}</p>
            </div>
            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $record->status === 'completed' ? 'bg-green-100 text-green-800' : ($record->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">{{ ucfirst($record->status) }}</span>
        </div>
    </a>
    @empty
    <div class="text-center py-12 text-gray-400"><svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg><p>No maintenance reports.</p></div>
    @endforelse
    <div>{{ $records->links() }}</div>
</div>
@endsection
