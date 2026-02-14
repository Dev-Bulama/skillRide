@extends('layouts.manager')
@section('title', 'Request Payout')
@section('header', 'Request Payout')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <a href="{{ route('manager.payments.index') }}" class="text-sm text-blue-600 hover:text-blue-700">&larr; Back to Payments</a>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Payout Request Form</h3>

        @if($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
            <ul class="list-disc list-inside">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="{{ route('manager.payments.payout.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount (NGN) *</label>
                <input type="number" name="amount" step="0.01" min="0" required value="{{ old('amount') }}" class="mt-1 block w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="0.00">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Period Start *</label>
                    <input type="date" name="period_start" required value="{{ old('period_start') }}" class="mt-1 block w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Period End *</label>
                    <input type="date" name="period_end" required value="{{ old('period_end') }}" class="mt-1 block w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <hr class="border-gray-200 dark:border-gray-700">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Bank Details</h4>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bank Name *</label>
                <input type="text" name="bank_name" required value="{{ old('bank_name', $manager->managerProfile->bank_name ?? '') }}" class="mt-1 block w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. First Bank">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account Number *</label>
                <input type="text" name="bank_account_number" required value="{{ old('bank_account_number', $manager->managerProfile->bank_account_number ?? '') }}" class="mt-1 block w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="0123456789">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account Name *</label>
                <input type="text" name="bank_account_name" required value="{{ old('bank_account_name', $manager->managerProfile->bank_account_name ?? '') }}" class="mt-1 block w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Account holder name">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                <textarea name="notes" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Additional details...">{{ old('notes') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600">Submit Request</button>
                <a href="{{ route('manager.payments.history') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200">View History</a>
            </div>
        </form>
    </div>
</div>
@endsection
