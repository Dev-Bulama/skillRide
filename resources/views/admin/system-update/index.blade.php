@extends('layouts.admin')
@section('title', 'System Updates')
@section('header', 'System Updates')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
<div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
<h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Upload Update</h3>
<div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg mb-4 text-sm text-yellow-700">Create a backup before applying updates. Updates cannot always be reversed.</div>
<form method="POST" action="{{ route('admin.system-updates.upload') }}" enctype="multipart/form-data" class="space-y-4">@csrf
<div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">ZIP File *</label><input type="file" name="file" required accept=".zip" class="mt-1 block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">@error('file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
<div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Version *</label><input type="text" name="version" required placeholder="e.g. 1.2.0" class="mt-1 block w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">@error('version')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
<div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label><textarea name="description" rows="3" placeholder="What changed..." class="mt-1 block w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500"></textarea></div>
<button type="submit" class="w-full py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600">Upload Update</button>
</form></div>
<div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700"><h3 class="text-lg font-semibold text-gray-800 dark:text-white">Update History</h3></div>
<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
<thead class="bg-gray-50 dark:bg-gray-900"><tr><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Version</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uploaded By</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th><th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th><th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th></tr></thead>
<tbody class="divide-y divide-gray-200 dark:divide-gray-700">
@forelse($updates as $update)
<tr><td class="px-4 py-3 text-sm font-medium text-gray-800 dark:text-white">v{{ $update->version }}</td>
<td class="px-4 py-3 text-sm text-gray-600">{{ $update->uploadedBy?->name }}</td>
<td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full {{ $update->status === 'completed' ? 'bg-green-100 text-green-800' : ($update->status === 'failed' ? 'bg-red-100 text-red-800' : ($update->status === 'processing' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">{{ ucfirst($update->status) }}</span></td>
<td class="px-4 py-3 text-sm text-gray-500">{{ $update->created_at->format('M d, Y') }}</td>
<td class="px-4 py-3 text-right space-x-1">
@if($update->status === 'pending')<form method="POST" action="{{ route('admin.system-updates.apply', $update) }}" class="inline" onsubmit="return confirm('Apply this update?')">@csrf<button class="px-3 py-1 bg-emerald-500 text-white text-xs rounded-lg hover:bg-emerald-600">Apply</button></form>@endif
@if($update->status === 'completed')<form method="POST" action="{{ route('admin.system-updates.rollback', $update) }}" class="inline" onsubmit="return confirm('Rollback?')">@csrf<button class="px-3 py-1 bg-red-500 text-white text-xs rounded-lg hover:bg-red-600">Rollback</button></form>@endif
</td></tr>
@empty<tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">No updates uploaded.</td></tr>@endforelse
</tbody></table></div></div>
@endsection
