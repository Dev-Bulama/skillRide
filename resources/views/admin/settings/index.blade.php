@extends('layouts.admin')
@section('title', 'Settings')
@section('header', 'System Settings')

@section('content')
<div class="space-y-6">
    {{-- Settings Navigation Tabs --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex space-x-1 p-2">
                <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 text-sm font-medium rounded-lg bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400">General</a>
                <a href="{{ route('admin.settings.branding') }}" class="px-4 py-2 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Branding</a>
                <a href="{{ route('admin.settings.notifications') }}" class="px-4 py-2 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Notifications</a>
                <a href="{{ route('admin.settings.routes') }}" class="px-4 py-2 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Routes</a>
            </nav>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- General Settings --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">General Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Application Name</label>
                    <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name'] ?? config('app.name')) }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('app_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Support Email</label>
                    <input type="email" name="support_email" value="{{ old('support_email', $settings['support_email'] ?? '') }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('support_email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Support Phone</label>
                    <input type="tel" name="support_phone" value="{{ old('support_phone', $settings['support_phone'] ?? '') }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Currency</label>
                    <select name="currency" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="NGN" {{ old('currency', $settings['currency'] ?? 'NGN') === 'NGN' ? 'selected' : '' }}>NGN (&#8358;)</option>
                        <option value="USD" {{ old('currency', $settings['currency'] ?? 'NGN') === 'USD' ? 'selected' : '' }}>USD ($)</option>
                        <option value="GBP" {{ old('currency', $settings['currency'] ?? 'NGN') === 'GBP' ? 'selected' : '' }}>GBP (&pound;)</option>
                        <option value="EUR" {{ old('currency', $settings['currency'] ?? 'NGN') === 'EUR' ? 'selected' : '' }}>EUR (&euro;)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Timezone</label>
                    <select name="timezone" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="Africa/Lagos" {{ old('timezone', $settings['timezone'] ?? 'Africa/Lagos') === 'Africa/Lagos' ? 'selected' : '' }}>Africa/Lagos (WAT)</option>
                        <option value="Africa/Johannesburg" {{ old('timezone', $settings['timezone'] ?? '') === 'Africa/Johannesburg' ? 'selected' : '' }}>Africa/Johannesburg</option>
                        <option value="Africa/Nairobi" {{ old('timezone', $settings['timezone'] ?? '') === 'Africa/Nairobi' ? 'selected' : '' }}>Africa/Nairobi</option>
                        <option value="UTC" {{ old('timezone', $settings['timezone'] ?? '') === 'UTC' ? 'selected' : '' }}>UTC</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Paystack Integration --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Paystack Integration</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Enter your Paystack API keys to enable online payment collection from riders.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Paystack Public Key</label>
                    <input type="text" name="paystack_public_key" value="{{ old('paystack_public_key', $settings['paystack_public_key'] ?? '') }}" placeholder="pk_live_..." class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 font-mono text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Paystack Secret Key</label>
                    <input type="password" name="paystack_secret_key" value="{{ old('paystack_secret_key', $settings['paystack_secret_key'] ?? '') }}" placeholder="sk_live_..." class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 font-mono text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Paystack Webhook URL</label>
                    <input type="text" readonly value="{{ url('/api/paystack/webhook') }}" class="mt-1 block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-500 text-sm cursor-not-allowed">
                    <p class="text-xs text-gray-400 mt-1">Copy this URL to your Paystack dashboard webhook settings.</p>
                </div>
                <div class="flex items-center mt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="paystack_live_mode" value="1" {{ old('paystack_live_mode', $settings['paystack_live_mode'] ?? false) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Live Mode</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Payment Settings --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Payment Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Default Daily Rate (&#8358;)</label>
                    <input type="number" name="default_daily_rate" value="{{ old('default_daily_rate', $settings['default_daily_rate'] ?? '') }}" step="0.01" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grace Period (days)</label>
                    <input type="number" name="grace_period" value="{{ old('grace_period', $settings['grace_period'] ?? 3) }}" min="0" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Late Fee (%)</label>
                    <input type="number" name="late_fee_percentage" value="{{ old('late_fee_percentage', $settings['late_fee_percentage'] ?? 0) }}" step="0.01" min="0" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Auto-Suspend After (days overdue)</label>
                    <input type="number" name="auto_suspend_days" value="{{ old('auto_suspend_days', $settings['auto_suspend_days'] ?? 30) }}" min="0" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>
        </div>

        {{-- GPS Settings --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">GPS & Tracking Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">GPS Update Interval (seconds)</label>
                    <input type="number" name="gps_interval" value="{{ old('gps_interval', $settings['gps_interval'] ?? 30) }}" min="5" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div class="flex items-center mt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="gps_tracking_enabled" value="1" {{ old('gps_tracking_enabled', $settings['gps_tracking_enabled'] ?? true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Enable GPS Tracking</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg text-sm font-medium hover:from-emerald-600 hover:to-emerald-700">Save Settings</button>
        </div>
    </form>
</div>
@endsection
