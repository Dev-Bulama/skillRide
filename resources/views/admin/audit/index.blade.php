@extends('layouts.admin')
@section('title', 'Audit Logs')
@section('header', 'Audit Logs')
@section('content')
<form method="GET" class="mb-4 flex flex-wrap gap-2">
<select name="user_id" class="rounded-lg border-gray-300 text-sm focus:ring-emerald-500"><option value="">All Users</option>@foreach($users as $u)<option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>@endforeach</select>
<input type="text" name="action" value="{{ request('action') }}" placeholder="Action..." class="rounded-lg border-gray-300 text-sm focus:ring-emerald-500">
<input type="date" name="from" value="{{ request('from') }}" class="rounded-lg border-gray-300 text-sm focus:ring-emerald-500">
<input type="date" name="to" value="{{ request('to') }}" class="rounded-lg border-gray-300 text-sm focus:ring-emerald-500">
<button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded-lg text-sm hover:bg-emerald-600">Filter</button>
</form>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
<div class="divide-y divide-gray-200 dark:divide-gray-700">
@forelse($logs as $log)
<div class="px-4 py-3 flex items-start gap-3">
<div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 text-xs font-medium shrink-0">{{ substr($log->user?->name ?? '?', 0, 1) }}</div>
<div class="flex-1 min-w-0"><p class="text-sm text-gray-800 dark:text-white"><span class="font-medium">{{ $log->user?->name ?? 'System' }}</span> <span class="text-gray-500">{{ $log->action }}</span></p><p class="text-xs text-gray-500">{{ $log->description }}</p><p class="text-xs text-gray-400 mt-1">{{ $log->created_at->format('M d, Y h:i A') }} | IP: {{ $log->ip_address }}</p></div>
<a href="{{ route('admin.audit.show', $log) }}" class="text-xs text-blue-600 hover:underline shrink-0">Detail</a>
</div>
@empty<div class="px-4 py-8 text-center text-gray-500">No audit logs.</div>@endforelse
</div><div class="px-4 py-3 border-t">{{ $logs->links() }}</div></div>
@endsection
