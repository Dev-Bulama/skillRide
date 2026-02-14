@extends('layouts.admin')
@section('title', 'Branding')
@section('header', 'Branding Settings')
@section('content')
<div class="max-w-2xl"><form method="POST" action="{{ route('admin.settings.branding.update') }}" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">@csrf @method('PUT')
<div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Company Logo</label><input type="file" name="logo" accept="image/*" class="mt-1 block w-full text-sm"></div>
<div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Company Name</label><input type="text" name="company_name" value="{{ $settings['company_name'] ?? '' }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></div>
<div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Primary Color</label><input type="color" name="primary_color" value="{{ $settings['primary_color'] ?? '#10B981' }}" class="mt-1 h-10 w-20 rounded border-gray-300"></div>
<div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Secondary Color</label><input type="color" name="secondary_color" value="{{ $settings['secondary_color'] ?? '#3B82F6' }}" class="mt-1 h-10 w-20 rounded border-gray-300"></div>
<button type="submit" class="px-6 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600">Save Branding</button>
</form></div>
@endsection
