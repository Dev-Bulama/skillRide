@extends('layouts.admin')
@section('title', 'User Activity')
@section('header', 'Activity: ' . $user->name)
@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
<div class="divide-y divide-gray-200 dark:divide-gray-700">
@forelse($logs as $log)
<div class="px-4 py-3"><p class="text-sm"><span class="font-medium text-gray-800 dark:text-white">{{ $log->action }}</span> <span class="text-gray-500">- {{ $log->description }}</span></p><p class="text-xs text-gray-400 mt-1">{{ $log->created_at->format('M d, Y h:i A') }} | {{ $log->ip_address }}</p></div>
@empty<div class="px-4 py-8 text-center text-gray-500">No activity.</div>@endforelse
</div><div class="px-4 py-3 border-t">{{ $logs->links() }}</div></div>
@endsection
