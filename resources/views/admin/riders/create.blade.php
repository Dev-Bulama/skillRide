@extends('layouts.admin')
@section('title', 'Add Rider')
@section('header', 'Add New Rider')
@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('admin.riders.store') }}" class="space-y-6">
        @csrf
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Personal Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name *</label><input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">@error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email *</label><input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">@error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone *</label><input type="tel" name="phone" value="{{ old('phone') }}" required class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">@error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password *</label><input type="password" name="password" required class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">@error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label><input type="text" name="address" value="{{ old('address') }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label><input type="text" name="city" value="{{ old('city') }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">State</label><input type="text" name="state" value="{{ old('state') }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gender</label><select name="gender" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"><option value="">Select</option><option value="male">Male</option><option value="female">Female</option></select></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Birth</label><input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Guarantor Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Guarantor Name</label><input type="text" name="guarantor_name" value="{{ old('guarantor_name') }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Guarantor Phone</label><input type="tel" name="guarantor_phone" value="{{ old('guarantor_phone') }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></div>
                <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Guarantor Address</label><input type="text" name="guarantor_address" value="{{ old('guarantor_address') }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Assignment</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assign Vehicle</label><select name="vehicle_id" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"><option value="">None</option>@foreach($vehicles as $v)<option value="{{ $v->id }}">{{ $v->registration_number }} - {{ $v->make }} {{ $v->model }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Assign Route</label><select name="route_id" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"><option value="">None</option>@foreach($routes as $r)<option value="{{ $r->id }}">{{ $r->name }}</option>@endforeach</select></div>
            </div>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.riders.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg text-sm font-medium hover:from-emerald-600 hover:to-emerald-700">Create Rider</button>
        </div>
    </form>
</div>
@endsection
