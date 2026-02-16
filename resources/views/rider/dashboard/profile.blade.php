@extends('layouts.rider')
@section('title', 'Profile')
@section('content')
<div class="space-y-4 pb-20">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm text-center">
        <div class="w-20 h-20 mx-auto bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 font-bold text-2xl mb-4">{{ substr($rider->name, 0, 1) }}</div>
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $rider->name }}</h2>
        <p class="text-sm text-gray-500">{{ $rider->email }}</p>
        <div class="flex justify-center gap-2 mt-3">
            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $rider->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($rider->status) }}</span>
            @if($rider->is_verified)<span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Verified</span>@endif
        </div>
        <a href="{{ route('rider.profile.edit') }}" class="mt-4 inline-block px-6 py-2 bg-emerald-500 text-white rounded-lg text-sm font-medium hover:bg-emerald-600">Edit Profile</a>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm space-y-3 text-sm">
        <div class="flex justify-between"><span class="text-gray-500">Phone:</span><span class="text-gray-800 dark:text-white">{{ $rider->phone }}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">Address:</span><span class="text-gray-800 dark:text-white">{{ $rider->address ?? 'N/A' }}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">City:</span><span class="text-gray-800 dark:text-white">{{ $rider->city ?? 'N/A' }}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">State:</span><span class="text-gray-800 dark:text-white">{{ $rider->state ?? 'N/A' }}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">Gender:</span><span class="text-gray-800 dark:text-white">{{ ucfirst($rider->gender ?? 'N/A') }}</span></div>
    </div>
    @if($rider->riderProfile)
    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
        <h3 class="font-semibold text-gray-800 dark:text-white mb-3">Documents</h3>

        {{-- Uploaded Documents --}}
        @php
            $documents = [
                'id_document_path' => 'ID Document',
                'drivers_license_path' => "Driver's License",
                'passport_photo_path' => 'Passport Photo',
                'facial_verification_path' => 'Facial Verification',
            ];
        @endphp
        <div class="space-y-2 mb-4">
            @foreach($documents as $field => $label)
                <div class="flex items-center justify-between p-2 rounded-lg {{ $rider->riderProfile->$field ? 'bg-emerald-50' : 'bg-gray-50' }}">
                    <div class="flex items-center">
                        @if($rider->riderProfile->$field)
                            <svg class="w-4 h-4 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        @endif
                        <span class="text-sm {{ $rider->riderProfile->$field ? 'text-emerald-700 font-medium' : 'text-gray-500' }}">{{ $label }}</span>
                    </div>
                    @if($rider->riderProfile->$field)
                        <a href="{{ Storage::url($rider->riderProfile->$field) }}" target="_blank" class="text-xs text-blue-600 hover:underline">View</a>
                    @else
                        <span class="text-xs text-gray-400">Not uploaded</span>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Upload Form --}}
        <form method="POST" action="{{ route('rider.profile.document') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <select name="document_type" required class="block w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">Select document type</option>
                <option value="id_document">ID Document</option>
                <option value="drivers_license">Driver's License</option>
                <option value="passport_photo">Passport Photo</option>
                <option value="facial_verification">Facial Verification</option>
            </select>
            <input type="file" name="document" required accept="image/*,.pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:text-emerald-700">
            <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded-lg text-sm font-medium hover:bg-blue-600">Upload</button>
        </form>
    </div>
    @endif
</div>
@endsection
