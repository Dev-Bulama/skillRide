@extends('layouts.admin')

@section('title', 'Add Vehicle')
@section('header', 'Register New Vehicle')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.vehicles.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-emerald-600 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Vehicles
        </a>
    </div>

    <form action="{{ route('admin.vehicles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Vehicle Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="plate_number" class="block text-sm font-medium text-gray-700 mb-1">Plate Number <span class="text-red-500">*</span></label>
                    <input type="text" name="plate_number" id="plate_number" value="{{ old('plate_number') }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('plate_number') border-red-300 @enderror"
                        placeholder="e.g., KMFL 123A">
                    @error('plate_number')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type <span class="text-red-500">*</span></label>
                    <select name="type" id="type" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('type') border-red-300 @enderror">
                        <option value="">Select type</option>
                        <option value="motorcycle" {{ old('type') === 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                        <option value="bicycle" {{ old('type') === 'bicycle' ? 'selected' : '' }}>Bicycle</option>
                        <option value="scooter" {{ old('type') === 'scooter' ? 'selected' : '' }}>Scooter</option>
                        <option value="electric_bike" {{ old('type') === 'electric_bike' ? 'selected' : '' }}>Electric Bike</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="make" class="block text-sm font-medium text-gray-700 mb-1">Make <span class="text-red-500">*</span></label>
                    <input type="text" name="make" id="make" value="{{ old('make') }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('make') border-red-300 @enderror"
                        placeholder="e.g., Honda">
                    @error('make')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Model <span class="text-red-500">*</span></label>
                    <input type="text" name="model" id="model" value="{{ old('model') }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('model') border-red-300 @enderror"
                        placeholder="e.g., CB125F">
                    @error('model')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                    <input type="number" name="year" id="year" value="{{ old('year') }}" min="2000" max="{{ date('Y') + 1 }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('year') border-red-300 @enderror"
                        placeholder="{{ date('Y') }}">
                    @error('year')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <input type="text" name="color" id="color" value="{{ old('color') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('color') border-red-300 @enderror"
                        placeholder="e.g., Red">
                    @error('color')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="engine_number" class="block text-sm font-medium text-gray-700 mb-1">Engine Number</label>
                    <input type="text" name="engine_number" id="engine_number" value="{{ old('engine_number') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('engine_number') border-red-300 @enderror"
                        placeholder="Engine serial number">
                    @error('engine_number')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="chassis_number" class="block text-sm font-medium text-gray-700 mb-1">Chassis Number</label>
                    <input type="text" name="chassis_number" id="chassis_number" value="{{ old('chassis_number') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('chassis_number') border-red-300 @enderror"
                        placeholder="Chassis/VIN number">
                    @error('chassis_number')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Registration & Insurance --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Registration & Insurance</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="registration_date" class="block text-sm font-medium text-gray-700 mb-1">Registration Date</label>
                    <input type="date" name="registration_date" id="registration_date" value="{{ old('registration_date') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('registration_date') border-red-300 @enderror">
                    @error('registration_date')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="insurance_expiry" class="block text-sm font-medium text-gray-700 mb-1">Insurance Expiry Date</label>
                    <input type="date" name="insurance_expiry" id="insurance_expiry" value="{{ old('insurance_expiry') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('insurance_expiry') border-red-300 @enderror">
                    @error('insurance_expiry')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="insurance_provider" class="block text-sm font-medium text-gray-700 mb-1">Insurance Provider</label>
                    <input type="text" name="insurance_provider" id="insurance_provider" value="{{ old('insurance_provider') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('insurance_provider') border-red-300 @enderror"
                        placeholder="e.g., Jubilee Insurance">
                    @error('insurance_provider')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="insurance_policy_number" class="block text-sm font-medium text-gray-700 mb-1">Policy Number</label>
                    <input type="text" name="insurance_policy_number" id="insurance_policy_number" value="{{ old('insurance_policy_number') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('insurance_policy_number') border-red-300 @enderror"
                        placeholder="Insurance policy number">
                    @error('insurance_policy_number')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Assignment --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Assignment</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="rider_id" class="block text-sm font-medium text-gray-700 mb-1">Assign to Rider</label>
                    <select name="rider_id" id="rider_id"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('rider_id') border-red-300 @enderror">
                        <option value="">-- No rider assigned --</option>
                        @foreach($riders as $rider)
                            <option value="{{ $rider->id }}" {{ old('rider_id') == $rider->id ? 'selected' : '' }}>
                                {{ $rider->name }} ({{ $rider->phone ?? $rider->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('rider_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="manager_id" class="block text-sm font-medium text-gray-700 mb-1">Managed By</label>
                    <select name="manager_id" id="manager_id"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('manager_id') border-red-300 @enderror">
                        <option value="">-- Select manager --</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                {{ $manager->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('status') border-red-300 @enderror">
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="daily_rate" class="block text-sm font-medium text-gray-700 mb-1">Daily Rate (KES)</label>
                    <input type="number" name="daily_rate" id="daily_rate" value="{{ old('daily_rate') }}" step="0.01" min="0"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('daily_rate') border-red-300 @enderror"
                        placeholder="0.00">
                    @error('daily_rate')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Vehicle Image --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Image</h3>
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Upload Image</label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 @error('image') border-red-300 @enderror">
                @error('image')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-400">Accepted formats: JPG, PNG, WEBP. Max size: 5MB</p>
            </div>
        </div>

        {{-- Notes --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Notes</h3>
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('notes') border-red-300 @enderror"
                    placeholder="Any additional notes about this vehicle...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.vehicles.index') }}" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-emerald-500 text-white text-sm font-medium rounded-lg hover:bg-emerald-600 transition-colors shadow-sm">
                Register Vehicle
            </button>
        </div>
    </form>
</div>
@endsection
