@extends('layouts.admin')
@section('title', 'Payment Plans')
@section('header', 'Payment Plans')

@section('content')
<div class="space-y-6">
    {{-- Existing Plans --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($plans as $plan)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $plan->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $plan->description ?? '' }}</p>
                </div>
                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ ($plan->status ?? 'active') === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($plan->status ?? 'active') }}
                </span>
            </div>
            <div class="space-y-2 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Amount</span>
                    <span class="font-bold text-emerald-600">&#8358;{{ number_format($plan->amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Frequency</span>
                    <span class="text-gray-900 dark:text-white capitalize">{{ $plan->frequency ?? 'daily' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Duration</span>
                    <span class="text-gray-900 dark:text-white">{{ $plan->duration ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="flex gap-2 pt-4 border-t border-gray-100 dark:border-gray-700">
                <form method="POST" action="{{ route('admin.payments.plans.toggle', $plan) }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full px-3 py-1.5 text-xs font-medium rounded-lg {{ ($plan->status ?? 'active') === 'active' ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' }}">
                        {{ ($plan->status ?? 'active') === 'active' ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.payments.plans.destroy', $plan) }}" onsubmit="return confirm('Delete this plan?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-red-100 text-red-700 rounded-lg hover:bg-red-200">Delete</button>
                </form>
            </div>
        </div>
        @empty
        <div class="sm:col-span-2 lg:col-span-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 text-center text-gray-500">
            No payment plans created yet.
        </div>
        @endforelse
    </div>

    {{-- Create New Plan --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Create New Plan</h3>
        <form method="POST" action="{{ route('admin.payments.plans.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Plan Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount (&#8358;) *</label>
                    <input type="number" name="amount" value="{{ old('amount') }}" step="0.01" required class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Frequency *</label>
                    <select name="frequency" required class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="daily" {{ old('frequency') === 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ old('frequency') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ old('frequency') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                    @error('frequency')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration</label>
                    <input type="text" name="duration" value="{{ old('duration') }}" placeholder="e.g. 30 days" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <input type="text" name="description" value="{{ old('description') }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg text-sm font-medium hover:from-emerald-600 hover:to-emerald-700">Create Plan</button>
            </div>
        </form>
    </div>
</div>
@endsection
