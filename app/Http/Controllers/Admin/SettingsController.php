<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token', '_method') as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return back()->with('success', 'Settings updated.');
    }

    public function branding()
    {
        $settings = SiteSetting::where('group', 'branding')->pluck('value', 'key');
        return view('admin.settings.branding', compact('settings'));
    }

    public function updateBranding(Request $request)
    {
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('branding', 'public');
            SiteSetting::updateOrCreate(['key' => 'logo'], ['value' => $path, 'group' => 'branding', 'type' => 'file']);
        }

        foreach (['primary_color', 'secondary_color', 'company_name'] as $field) {
            if ($request->filled($field)) {
                SiteSetting::updateOrCreate(['key' => $field], ['value' => $request->$field, 'group' => 'branding']);
            }
        }

        return back()->with('success', 'Branding updated.');
    }

    public function notifications()
    {
        $settings = SiteSetting::where('group', 'notifications')->pluck('value', 'key');
        return view('admin.settings.notifications', compact('settings'));
    }

    public function updateNotifications(Request $request)
    {
        foreach (['sms_enabled', 'email_enabled', 'push_enabled', 'payment_reminder_hours', 'maintenance_reminder_days'] as $field) {
            SiteSetting::updateOrCreate(
                ['key' => $field],
                ['value' => $request->input($field, '0'), 'group' => 'notifications']
            );
        }
        return back()->with('success', 'Notification settings updated.');
    }

    public function routes()
    {
        $routes = Route::with('assignedManager')->orderBy('name')->get();
        return view('admin.settings.routes', compact('routes'));
    }

    public function storeRoute(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_location' => 'required|string',
            'end_location' => 'required|string',
            'ward' => 'nullable|string',
            'lga' => 'nullable|string',
            'state' => 'nullable|string',
            'assigned_manager_id' => 'nullable|exists:users,id',
        ]);

        Route::create(array_merge($validated, ['status' => 'active']));
        return back()->with('success', 'Route created.');
    }

    public function updateRoute(Request $request, Route $route)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_location' => 'required|string',
            'end_location' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $route->update($validated);
        return back()->with('success', 'Route updated.');
    }

    public function deleteRoute(Route $route)
    {
        $route->delete();
        return back()->with('success', 'Route deleted.');
    }
}
