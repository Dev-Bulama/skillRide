@extends('layouts.manager')
@section('title', 'Maintenance')
@section('header', 'Maintenance Records')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <a href="{{ route('manager.maintenance.schedules') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600 text-center">View Schedules</a>
        <form method="GET" class="flex gap-2">
            <select name="status" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Statuses</option>
                @foreach(['pending', 'in_progress', 'completed', 'cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>
            <select name="priority" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Priorities</option>
                @foreach(['low', 'medium', 'high', 'critical'] as $p)
                <option value="{{ $p }}" {{ request('priority') === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reported By</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($records as $record)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-3">
                        <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $record->vehicle->registration_number ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $record->vehicle->make ?? '' }} {{ $record->vehicle->model ?? '' }}</p>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ ucfirst(str_replace('_', ' ', $record->type)) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $record->reportedBy->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full {{ $record->priority === 'critical' ? 'bg-red-100 text-red-800' : ($record->priority === 'high' ? 'bg-orange-100 text-orange-800' : ($record->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">{{ ucfirst($record->priority) }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full {{ $record->status === 'completed' ? 'bg-green-100 text-green-800' : ($record->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : ($record->status === 'cancelled' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800')) }}">{{ ucfirst(str_replace('_', ' ', $record->status)) }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $record->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-right space-x-1">
                        <a href="{{ route('manager.maintenance.show', $record) }}" class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-lg hover:bg-gray-200">View</a>
                        @if($record->status === 'pending' || $record->status === 'in_progress')
                        <form method="POST" action="{{ route('manager.maintenance.approve', $record) }}" class="inline" onsubmit="return confirm('Mark as completed?')">
                            @csrf
                            <button class="px-3 py-1 bg-emerald-500 text-white text-xs rounded-lg hover:bg-emerald-600">Approve</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No maintenance records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div>{{ $records->links() }}</div>
</div>
@endsection
