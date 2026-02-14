@extends('layouts.manager')
@section('title', 'Maintenance Schedules')
@section('header', 'Maintenance Schedules')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('manager.maintenance.index') }}" class="text-sm text-blue-600 hover:text-blue-700">&larr; Back to Maintenance</a>
        <span class="px-3 py-1 bg-orange-100 text-orange-700 text-sm font-medium rounded-full">{{ $upcoming->count() }} upcoming</span>
    </div>

    @if($upcoming->count() > 0)
    <div class="space-y-3">
        @foreach($upcoming as $record)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center {{ $record->priority === 'critical' ? 'bg-red-100' : ($record->priority === 'high' ? 'bg-orange-100' : 'bg-blue-100') }}">
                        <svg class="w-6 h-6 {{ $record->priority === 'critical' ? 'text-red-600' : ($record->priority === 'high' ? 'text-orange-600' : 'text-blue-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800 dark:text-white">{{ ucfirst(str_replace('_', ' ', $record->type)) }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $record->vehicle->registration_number ?? 'N/A' }} - {{ $record->vehicle->make ?? '' }} {{ $record->vehicle->model ?? '' }}</p>
                        @if($record->description)<p class="text-xs text-gray-400 mt-1">{{ Str::limit($record->description, 80) }}</p>@endif
                    </div>
                </div>
                <div class="text-right">
                    <span class="px-2 py-1 text-xs rounded-full {{ $record->priority === 'critical' ? 'bg-red-100 text-red-800' : ($record->priority === 'high' ? 'bg-orange-100 text-orange-800' : ($record->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">{{ ucfirst($record->priority) }}</span>
                    @if($record->next_due_date)
                    <p class="text-xs text-gray-500 mt-2">Due: {{ \Carbon\Carbon::parse($record->next_due_date)->format('M d, Y') }}</p>
                    @if(\Carbon\Carbon::parse($record->next_due_date)->isPast())
                    <p class="text-xs text-red-500 font-medium">Overdue</p>
                    @elseif(\Carbon\Carbon::parse($record->next_due_date)->diffInDays(now()) <= 7)
                    <p class="text-xs text-orange-500 font-medium">Due soon</p>
                    @endif
                    @endif
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <span class="px-2 py-1 text-xs rounded-full {{ $record->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst(str_replace('_', ' ', $record->status)) }}</span>
                <a href="{{ route('manager.maintenance.show', $record) }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View &rarr;</a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-gray-400">No upcoming maintenance schedules.</p>
        <p class="text-sm text-gray-400 mt-1">All vehicles are up to date.</p>
    </div>
    @endif
</div>
@endsection
