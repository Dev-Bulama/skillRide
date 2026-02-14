@extends('layouts.rider')
@section('title', 'Edit Profile')
@section('content')
<div class="space-y-4 pb-20">
    <h1 class="text-xl font-bold text-gray-800 dark:text-white">Edit Profile</h1>
    <form method="POST" action="{{ route('rider.profile.update') }}" class="space-y-4">
        @csrf @method('PUT')
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm space-y-4">
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label><input type="text" name="name" value="{{ old('name', $rider->name) }}" required class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">@error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label><input type="tel" name="phone" value="{{ old('phone', $rider->phone) }}" required class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">@error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label><input type="text" name="address" value="{{ old('address', $rider->address) }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label><input type="text" name="city" value="{{ old('city', $rider->city) }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">State</label><input type="text" name="state" value="{{ old('state', $rider->state) }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Next of Kin Name</label><input type="text" name="next_of_kin_name" value="{{ old('next_of_kin_name', $rider->next_of_kin_name) }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Next of Kin Phone</label><input type="tel" name="next_of_kin_phone" value="{{ old('next_of_kin_phone', $rider->next_of_kin_phone) }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"></div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('rider.profile.show') }}" class="flex-1 py-3 text-center border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50">Cancel</a>
            <button type="submit" class="flex-1 py-3 bg-emerald-500 text-white rounded-xl font-medium hover:bg-emerald-600">Save Changes</button>
        </div>
    </form>
</div>
@endsection
