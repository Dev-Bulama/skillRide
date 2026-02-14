@extends('layouts.admin')
@section('title', 'Manager Details')
@section('header', 'Manager - ' . $manager->name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.managers.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-emerald-600">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Managers
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.managers.edit', $manager) }}" class="inline-flex items-center px-4 py-2 bg-emerald-500 text-white text-sm font-medium rounded-lg hover:bg-emerald-600">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            <form method="POST" action="{{ route('admin.managers.destroy', $manager) }}" onsubmit="return confirm('Delete this manager?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600">Delete</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Manager Info Card --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                <div class="flex flex-col items-center text-center mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-2xl mb-3">
                        {{ strtoupper(substr($manager->name, 0, 2)) }}
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $manager->name }}</h3>
                    <span class="inline-flex mt-2 px-3 py-1 text-xs font-medium rounded-full {{ $manager->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($manager->status ?? 'active') }}
                    </span>
                </div>
                <div class="space-y-3 border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Email</span>
                        <span class="text-gray-900 dark:text-white">{{ $manager->email }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Phone</span>
                        <span class="text-gray-900 dark:text-white">{{ $manager->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Department</span>
                        <span class="text-gray-900 dark:text-white">{{ $manager->department ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Zone</span>
                        <span class="text-gray-900 dark:text-white">{{ $manager->zone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Ward</span>
                        <span class="text-gray-900 dark:text-white">{{ $manager->ward ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Commission Rate</span>
                        <span class="text-emerald-600 font-medium">{{ $manager->commission_rate ?? 0 }}%</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Joined</span>
                        <span class="text-gray-900 dark:text-white">{{ $manager->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                {{-- Bank Details --}}
                <div class="border-t border-gray-200 dark:border-gray-700 mt-4 pt-4">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Bank Details</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Bank</span>
                            <span class="text-gray-900 dark:text-white">{{ $manager->bank_name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Account No.</span>
                            <span class="font-mono text-gray-900 dark:text-white">{{ $manager->bank_account_number ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Account Name</span>
                            <span class="text-gray-900 dark:text-white">{{ $manager->bank_account_name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Assigned Routes --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Assigned Routes</h3>
                </div>
                @if(isset($assignedRoutes) && $assignedRoutes->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Route Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">From / To</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Riders</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($assignedRoutes as $route)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $route->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $route->start_point ?? '' }} - {{ $route->end_point ?? '' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $route->riders_count ?? 0 }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ ($route->status ?? 'active') === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($route->status ?? 'active') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-8 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    <p class="text-sm">No routes assigned to this manager.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
