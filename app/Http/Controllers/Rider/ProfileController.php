<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $rider = auth()->user();
        $rider->load('riderProfile.assignedVehicle', 'riderProfile.assignedRoute');
        return view('rider.dashboard.profile', compact('rider'));
    }

    public function edit()
    {
        $rider = auth()->user();
        $rider->load('riderProfile');
        return view('rider.dashboard.edit-profile', compact('rider'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'next_of_kin_name' => 'nullable|string',
            'next_of_kin_phone' => 'nullable|string',
        ]);

        auth()->user()->update($validated);
        return redirect()->route('rider.profile.show')->with('success', 'Profile updated.');
    }

    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:id_document,drivers_license,passport_photo,facial_verification',
            'document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $rider = auth()->user();
        $path = $request->file('document')->store('documents/' . $rider->id, 'public');

        $field = $request->document_type . '_path';
        $rider->riderProfile?->update([$field => $path]);

        return back()->with('success', 'Document uploaded successfully.');
    }
}
