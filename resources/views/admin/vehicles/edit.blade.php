@extends('layouts.admin')

@section('title', 'Edit Vehicle')
@section('header', 'Edit Vehicle - ' . $vehicle->registration_number)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.vehicles.show', $vehicle) }}" class="inline-flex items-center text-sm text-gray-500 hover:text-emerald-600 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Vehicle Details
        </a>
    </div>

    <form action="{{ route('admin.vehicles.update', $vehicle) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Vehicle Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="registration_number" class="block text-sm font-medium text-gray-700 mb-1">Registration Number <span class="text-red-500">*</span></label>
                    <input type="text" name="registration_number" id="registration_number" value="{{ old('registration_number', $vehicle->registration_number) }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('registration_number') border-red-300 @enderror">
                    @error('registration_number')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type <span class="text-red-500">*</span></label>
                    <select name="type" id="type" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('type') border-red-300 @enderror">
                        <option value="tricycle" {{ old('type', $vehicle->type) === 'tricycle' ? 'selected' : '' }}>Tricycle (Keke)</option>
                        <option value="keke_napep" {{ old('type', $vehicle->type) === 'keke_napep' ? 'selected' : '' }}>Keke Napep</option>
                        <option value="motorcycle" {{ old('type', $vehicle->type) === 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                        <option value="car" {{ old('type', $vehicle->type) === 'car' ? 'selected' : '' }}>Car</option>
                        <option value="bus" {{ old('type', $vehicle->type) === 'bus' ? 'selected' : '' }}>Bus</option>
                        <option value="truck" {{ old('type', $vehicle->type) === 'truck' ? 'selected' : '' }}>Truck</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="make" class="block text-sm font-medium text-gray-700 mb-1">Make <span class="text-red-500">*</span></label>
                    <input type="text" name="make" id="make" value="{{ old('make', $vehicle->make) }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('make') border-red-300 @enderror">
                    @error('make')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Model <span class="text-red-500">*</span></label>
                    <input type="text" name="model" id="model" value="{{ old('model', $vehicle->model) }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('model') border-red-300 @enderror">
                    @error('model')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                    <input type="number" name="year" id="year" value="{{ old('year', $vehicle->year) }}" min="2000" max="{{ date('Y') + 1 }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('year') border-red-300 @enderror">
                    @error('year')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <input type="text" name="color" id="color" value="{{ old('color', $vehicle->color) }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('color') border-red-300 @enderror">
                    @error('color')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="engine_number" class="block text-sm font-medium text-gray-700 mb-1">Engine Number</label>
                    <input type="text" name="engine_number" id="engine_number" value="{{ old('engine_number', $vehicle->engine_number) }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('engine_number') border-red-300 @enderror">
                    @error('engine_number')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="chassis_number" class="block text-sm font-medium text-gray-700 mb-1">Chassis Number</label>
                    <input type="text" name="chassis_number" id="chassis_number" value="{{ old('chassis_number', $vehicle->chassis_number) }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('chassis_number') border-red-300 @enderror">
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
                    <input type="date" name="registration_date" id="registration_date"
                        value="{{ old('registration_date', $vehicle->registration_date ? $vehicle->registration_date->format('Y-m-d') : '') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('registration_date') border-red-300 @enderror">
                    @error('registration_date')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="insurance_expiry" class="block text-sm font-medium text-gray-700 mb-1">Insurance Expiry Date</label>
                    <input type="date" name="insurance_expiry" id="insurance_expiry"
                        value="{{ old('insurance_expiry', $vehicle->insurance_expiry ? $vehicle->insurance_expiry->format('Y-m-d') : '') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('insurance_expiry') border-red-300 @enderror">
                    @error('insurance_expiry')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="insurance_provider" class="block text-sm font-medium text-gray-700 mb-1">Insurance Provider</label>
                    <input type="text" name="insurance_provider" id="insurance_provider" value="{{ old('insurance_provider', $vehicle->insurance_provider) }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('insurance_provider') border-red-300 @enderror">
                    @error('insurance_provider')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="insurance_policy_number" class="block text-sm font-medium text-gray-700 mb-1">Policy Number</label>
                    <input type="text" name="insurance_policy_number" id="insurance_policy_number" value="{{ old('insurance_policy_number', $vehicle->insurance_policy_number) }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('insurance_policy_number') border-red-300 @enderror">
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
                            <option value="{{ $rider->id }}" {{ old('rider_id', $vehicle->rider_id) == $rider->id ? 'selected' : '' }}>
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
                            <option value="{{ $manager->id }}" {{ old('manager_id', $vehicle->manager_id) == $manager->id ? 'selected' : '' }}>
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
                        <option value="active" {{ old('status', $vehicle->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $vehicle->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="maintenance" {{ old('status', $vehicle->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="decommissioned" {{ old('status', $vehicle->status) === 'decommissioned' ? 'selected' : '' }}>Decommissioned</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="daily_rate" class="block text-sm font-medium text-gray-700 mb-1">Daily Rate (&#8358;)</label>
                    <input type="number" name="daily_rate" id="daily_rate" value="{{ old('daily_rate', $vehicle->daily_rate) }}" step="0.01" min="0"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('daily_rate') border-red-300 @enderror">
                    @error('daily_rate')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Vehicle Image --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Image</h3>
            @if($vehicle->image)
                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-2">Current Image:</p>
                    <img src="{{ asset('storage/' . $vehicle->image) }}" alt="{{ $vehicle->registration_number }}" class="w-48 h-32 object-cover rounded-lg border border-gray-200">
                </div>
            @endif
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Upload New Image</label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                @error('image')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-400">Leave empty to keep current image. Accepted: JPG, PNG, WEBP. Max: 5MB</p>
            </div>
        </div>

        {{-- Notes --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Notes</h3>
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500 @error('notes') border-red-300 @enderror">{{ old('notes', $vehicle->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.vehicles.show', $vehicle) }}" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-emerald-500 text-white text-sm font-medium rounded-lg hover:bg-emerald-600 transition-colors shadow-sm">
                Update Vehicle
            </button>
        </div>
    </form>
</div>
@endsection
