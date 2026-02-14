@extends('layouts.rider')
@section('title', 'Report Issue')
@section('content')
<div class="space-y-4 pb-20">
    <h1 class="text-xl font-bold text-gray-800 dark:text-white">Report Maintenance Issue</h1>
    <form method="POST" action="{{ route('rider.maintenance.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type *</label>
                <select name="type" required class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Select type</option>
                    <option value="routine" {{ old('type') === 'routine' ? 'selected' : '' }}>Routine</option>
                    <option value="repair" {{ old('type') === 'repair' ? 'selected' : '' }}>Repair</option>
                    <option value="emergency" {{ old('type') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                    <option value="inspection" {{ old('type') === 'inspection' ? 'selected' : '' }}>Inspection</option>
                </select>
                @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Priority *</label>
                <select name="priority" required class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ old('priority') === 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
                @error('priority')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description *</label>
                <textarea name="description" rows="4" required class="mt-1 block w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Describe the issue in detail...">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Photos/Videos * <span class="text-gray-400 font-normal">(Required)</span></label>
                <input type="file" name="media[]" multiple required accept="image/*,video/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-xs text-gray-400 mt-1">Upload at least one image or video as evidence.</p>
                @error('media')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                @error('media.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl font-semibold hover:from-emerald-600 hover:to-emerald-700 transition">Submit Report</button>
    </form>
</div>
@endsection
