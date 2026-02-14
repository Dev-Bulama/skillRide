@extends('layouts.admin')
@section('title', 'Notifications')
@section('header', 'Notification Settings')
@section('content')
<div class="max-w-2xl"><form method="POST" action="{{ route('admin.settings.notifications.update') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">@csrf @method('PUT')
@foreach(['sms_enabled' => 'SMS Notifications', 'email_enabled' => 'Email Notifications', 'push_enabled' => 'Push Notifications'] as $key => $label)
<label class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"><span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</span><input type="checkbox" name="{{ $key }}" value="1" {{ ($settings[$key] ?? '0') === '1' ? 'checked' : '' }} class="rounded text-emerald-500 focus:ring-emerald-500"></label>
@endforeach
<div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Reminder (hours before)</label><input type="number" name="payment_reminder_hours" value="{{ $settings['payment_reminder_hours'] ?? 24 }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></div>
<div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Maintenance Reminder (days before)</label><input type="number" name="maintenance_reminder_days" value="{{ $settings['maintenance_reminder_days'] ?? 7 }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></div>
<button type="submit" class="px-6 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600">Save</button>
</form></div>
@endsection
